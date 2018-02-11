<?php
namespace BDLocation\Models;

class Location
{
    public $name;
    public $shortName;
    public $bengaliName;
    public $website;
    public $longitude;
    public $latitude;

    public function __construct($name, $shotName, $bengaliName, $website, $longitude, $latitude)
    {
        $this->name = $name;
        $this->shortName = $shotName;
        $this->bengaliName = $bengaliName;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }
}