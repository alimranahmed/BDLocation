<?php

namespace BDLocation\Utilities;

use PDO;
use PDOException;

class Exporter
{
    public $server = "localhost";
    public $username = "homestead";
    public $password = "secret";
    public $database = "bd_locations";
    public $port = "33060";
    public $connection = null;
    public $dataDir = __DIR__ . '/../../data';

    public function __construct()
    {
        $this->connection = $this->connect();
    }

    public function connect()
    {
        echo "\nConnecting to database...\n";
        try {
            $connection = new PDO(
                "mysql:host={$this->server};dbname={$this->database};port={$this->port}",
                $this->username,
                $this->password,
                [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]
            );
            // set the PDO error mode to exception
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected to database successfully!\n";
            return $connection;

        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getLine() . ': ' . $e->getMessage() . "\n";
        }
        return null;
    }

    public function execute(PDO $conn, $query)
    {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function prepareArray($resultArray)
    {
        return [
            'name' => $resultArray['name'],
            'short_name' => $resultArray['short_name'],
            'bengali_name' => $resultArray['bn_name'],
            'website' => $resultArray['website'] ?? '',
            'longitude' => $resultArray['lon'] ?? '',
            'latitude' => $resultArray['lat'] ?? '',
        ];
    }

    public function createDir($path)
    {
        if (!is_dir($path)) {
            // dir doesn't exist, make it
            mkdir($path, 0777, true);
        }
    }

    public function export()
    {
        $divisions['parent'] = null;
        $districts['parent'] = 'division';
        $sub_districts['parent'] = 'district';
        $unions['parent'] = 'sub_district';

        $root = $this->dataDir;

        $divisionQuery = "select * from divisions";
        $divisionRows = $this->execute($this->connection, $divisionQuery);

        echo "Exporting from database...\n";
        //divisions
        foreach ($divisionRows as $division) {
            $divisions['data'][] = $this->prepareArray($division);
            $districtsQuery = "select * from districts where division_id='{$division['id']}'";
            $districtRows = $this->execute($this->connection, $districtsQuery);
            //districts
            foreach ($districtRows as $district) {
                $districts['data'][$division['short_name']][] = $this->prepareArray($district);
                $districtsQuery = "select * from upazilas where district_id='{$district['id']}'";
                $subDistrictRows = $this->execute($this->connection, $districtsQuery);
                //sub_districts
                foreach ($subDistrictRows as $subDistrict) {
                    $sub_districts['data'][$district['short_name']][] = $this->prepareArray($subDistrict);
                    $unionQuery = "select * from unions where upazila_id='{$subDistrict['id']}'";
                    $unionRows = $this->execute($this->connection, $unionQuery);
                    //Union
                    foreach ($unionRows as $union) {
                        $unions['data'][$subDistrict['short_name']][] = $this->prepareArray($union);
                    }

                    $this->createDir($root);
                    file_put_contents("$root/unions.json", $this->jsonDecode($unions));
                }
                file_put_contents("$root//sub_districts.json", $this->jsonDecode($sub_districts));
            }
            file_put_contents("$root/districts.json", $this->jsonDecode($districts));
        }
        file_put_contents("$root/divisions.json", $this->jsonDecode($divisions));
        echo "Exported successfully!\n";
        return true;
    }

    public function jsonDecode($json)
    {
        return json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}

/*
 * -- name
 * -- short_name
 * -- bengali_name
 * -- latitude
 * -- longitude
 * -- website
 */