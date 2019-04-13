<?php

namespace BDLocation\Utilities;

use PDO;
use PDOException;

/**
 * Class ThanaPatch: thana extracted from:
 * http://www.onlineicttutor.com/upazila-police-stations-districts-divisions-list-bangladesh/
 * @package BDLocation\Utilities
 */
class ThanaPatch
{
    public $server = "localhost";
    public $username = "root";
    public $password = "";
    public $database = "bd_locations";
    public $port = "3306";
    public $connection = null;
    public $dataDir = __DIR__ . '/../../data/new';

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
            'name' => trim($resultArray['name']),
            'short_name' => $resultArray['short_name'] ?? substr(strtolower($resultArray['name']), 0, 3),
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
            $preparedDivision = $this->prepareArray($division);

            $divisions['data'][] = $preparedDivision;

            $districtsQuery = "select * from districts where division_id='{$division['id']}'";
            $districtRows = $this->execute($this->connection, $districtsQuery);
            //districts
            foreach ($districtRows as $district) {
                $preparedDistrict = $this->prepareArray($district);
                $districts['data'][$preparedDivision['short_name']][] = $preparedDivision;
                $districtsQuery = "select * from upazilas where district_id='{$district['id']}'";
                $subDistrictRows = $this->execute($this->connection, $districtsQuery);
                //sub_districts
                foreach ($subDistrictRows as $subDistrict) {
                    $preparedSubDistrict = $this->prepareArray($subDistrict);
                    $sub_districts['data'][$preparedDistrict['short_name']][] = $preparedSubDistrict;
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

$thanaPatch = new ThanaPatch();
$thanaPatch->export();