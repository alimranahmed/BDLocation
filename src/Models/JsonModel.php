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
        $locations = $this->getFileContent($this->dataPath);
        $locations = $this->buildCollection($locations);

        //districts
        $locations = $this->getDirectories($this->dataPath);
        $locations = $this->getFileContent($locations);
        return $locations;
    }

    public function where($name, $value)
    {

    }

    private function getDirContents($path)
    {
        $directories = [];
        $directoryNames = array_diff(scandir($path), ['.', '..', '.DS_Store']);
        foreach ($directoryNames as $directoryName) {
            $directories[] = "$path/$directoryName";
        }
        return $directories;
    }

    private function getFile($path)
    {
        $contents = $this->getDirContents($path);
        foreach ($contents as $content) {
            echo "Search for {$this->schema} in {$content}\n";
            if (strpos($content, $this->schema) !== false) {
                return $content;
            }
        }
        return null;
    }

    private function getFiles($paths)
    {
        $files = [];
        if (is_array($paths)) {
            foreach ($paths as $path) {
                $file = $this->getFile($path);
                if (!is_null($file)) {
                    $files[] = $file;
                }
            }
        } else {
            $file = $this->getFile($paths);
            if (!is_null($file)) {
                $files[] = $file;
            }
        }
        return $files;
    }

    private function getFileContent($paths)
    {
        $contents = [];
        $files = $this->getFiles($paths);
        foreach ($files as $file) {
            $content = json_decode(file_get_contents($file), true);
            $contents = array_merge($contents, $content);
        }
        return $contents;
    }

    private function getDirectories($path)
    {
        $contents = $this->getDirContents($path);
        $directories = [];
        foreach ($contents as $content) {
            if (strpos($content, '.json') === false) {
                $directories[] = $content;
            }
        }
        return $directories;
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