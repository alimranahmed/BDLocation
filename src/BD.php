<?php
namespace BDLocation;

use BDLocation\Models\Model;

class BD
{
    /**
     * @param $name
     * @param $arguments
     * @return Model
     */
    public static function __callStatic($name, $arguments)
    {
        $className = '\\BDLocation\\Models\\'.ucfirst($name);
        return new $className;
    }
}
