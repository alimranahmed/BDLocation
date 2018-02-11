<?php
require __DIR__ . "/vendor/autoload.php";

//Export
/*
$exporter = new \BDLocation\Utilities\Exporter();
$exporter->export();
die;*/

use BDLocation\Models\BD;
//$result = BD::district()->all();
$result = BD::union()->all();
//$result = BD::subDistrict()->all();
//$result = BD::division()->all();
var_dump($result);