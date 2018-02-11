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

    public function where($name, $operator = '=', $value)
    {
        $this->schema->where();
    }
}