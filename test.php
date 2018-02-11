<?php
require __DIR__ . "/vendor/autoload.php";

use BDLocation\Models\BD;

var_dump(BD::district()->all());