<?php

namespace BDLocation\Models;

class JsonModel
{
    protected $schema;

    protected $dataPath;

    protected $collection;

    public function __construct($schema, $dataPath = __DIR__ . '/../../data')
    {
        $this->schema = $schema;

        $this->dataPath = $dataPath;
    }

    public function all()
    {
        //Get All
        //1. If file found then
        //1. 1. IF this is the file we are looking for then return the contents
        //2. IF file not found then scan all the directory
        //2.1. If found file found then
        //division
        $locations = $this->getFileContent("{$this->dataPath}/{$this->schema}.json");
        $locations = $locations['data'];
        if($this->schema != 'divisions'){
            $locations = $this->buildFlatArray($locations);
        }
        return $this->buildCollection($locations);
    }

    public function getWhere($name, $operator, $value)
    {

    }

    private function getFileContent($filePath)
    {
        $content = json_decode(file_get_contents($filePath), true);
        return $content;
    }

    private function buildFlatArray($locations){
        $locationArrays = array_values($locations);
        $flatLocationArray = [];
        foreach ($locationArrays as $locationArray){
            $flatLocationArray = array_merge($flatLocationArray, $locationArray);
        }
        return $flatLocationArray;
    }

    private function buildCollection($locations)
    {
        $collection = [];
        foreach ($locations as $location) {
            $collection[] = $this->buildLocation($location);
        }
        return $collection;
    }

    /**
     * @param $location
     * @return Location
     */
    private function buildLocation($location)
    {
        return new Location(
            $location['name'],
            $location['short_name'],
            $location['bengali_name'],
            $location['website'],
            $location['longitude'],
            $location['latitude']
        );
    }
}