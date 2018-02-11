<?php
header('Content-type: text/json; charset=UTF-8');

function execute($conn, $query)
{
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function prepareArray($name, $short_name, $bengali_name, $website, $latitude, $longitude)
{
    return compact('name', 'short_name', 'bengali_name', 'website', 'longitude', 'latitude');
}

function createDir($path){
    if (!is_dir($path)) {
        // dir doesn't exist, make it
        mkdir($path, 0777, true);
    }
}

$server = "localhost";
$username = "homestead";
$password = "secret";
$database = "bd_locations";
$port = "33060";

try {
    $conn = new PDO("mysql:host=$server;dbname=$database;port:$port", $username, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$root = '../data';
$divisions = [];
$districts = [];
$sub_districts = [];
$unions = [];

$divisionQuery = "select * from divisions";
$divisionRows = execute($conn, $divisionQuery);

//divisions
foreach ($divisionRows as $division) {
    $divisions[] = prepareArray($division['name'], $division['short_name'], $division['bn_name'], $division['website'] ?? '', $division['lon'] ?? '', $division['lat'] ?? '');
    $districtsQuery = "select * from districts where division_id='{$division['id']}'";
    $districtRows = execute($conn, $districtsQuery);
    //districts
    foreach ($districtRows as $district) {
        $districts[] = prepareArray($district['name'], $district['short_name'], $district['bn_name'], $district['website'] ?? '', $district['lon'] ?? '', $district['lat'] ?? '');
        $districtsQuery = "select * from upazilas where district_id='{$district['id']}'";
        $subDistrictRows = execute($conn, $districtsQuery);
        //sub_districts
        foreach ($subDistrictRows as $subDistrict) {
            $sub_districts[] = prepareArray($subDistrict['name'], $subDistrict['short_name'], $subDistrict['bn_name'], $subDistrict['website'] ?? '', $subDistrict['lon'] ?? '', $subDistrict['lat'] ?? '');
            $unionQuery = "select * from unions where upazila_id='{$subDistrict['id']}'";
            $unionRows = execute($conn, $unionQuery);
            //Union
            foreach ($unionRows as $union){
                $unions[] = prepareArray($union['name'], $union['short_name'], $union['bn_name'], $union['website'] ?? '', $union['lon'] ?? '', $union['lat'] ?? '');
            }

            createDir("$root/{$division['short_name']}/{$district['short_name']}/{$subDistrict['short_name']}");
            file_put_contents("$root/{$division['short_name']}/{$district['short_name']}/{$subDistrict['short_name']}/subdistricts.json", json_encode($sub_districts, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        }
        //createDir("{$division['short_name']}/{$district['short_name']}");
        file_put_contents("$root/{$division['short_name']}/{$district['short_name']}/subdistricts.json", json_encode($sub_districts, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
    }
    //createDir("{$division['short_name']}");
    file_put_contents("$root/{$division['short_name']}/districts.json", json_encode($districts, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
}
file_put_contents("$root/divisions.json", json_encode($divisions, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));



/*
 * -- Data
 * ---- divisions.json
 * ---- all divisions
 * ------ districts.json
 * ------ all districts
 * -------- sub_districts.json
 * -------- all sub districts
 * ---------- unions.json
 *
 * -- name
 * -- short_name
 * -- bengali_name
 * -- latitude
 * -- longitude
 * -- website
 */