<?php

namespace BDLocation\Models;

class Model
{
    protected $driver;
    protected $connection;
    protected $concreteModel;

    protected $schema;

    public function __construct($driver = 'json', $connection = null)
    {
        $this->driver = $driver;

        $concreteModelName = '\\BDLocation\\Models\\'.ucfirst(strtolower($driver)) . 'Model';
        $this->concreteModel = new $concreteModelName($this->schema);
    }

    public function all()
    {
        return $this->concreteModel->all();
    }

    /**
     * @param $name ['division', 'district', 'sub_district', 'name', 'short_name', 'bengali_name']
     * @param $operator ['=', 'like']
     * @param $value
     * @return array
     */
    public function getWhere($name, $operator, $value = null)
    {
        return $this->concreteModel->getWhere($name, $operator, $value);
    }
}
