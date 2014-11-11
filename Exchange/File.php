<?php

namespace TaskManager\Exchange;

use TaskManager\ExchangeInterface;

class File implements ExchangeInterface
{
    private $fileName;

    public function __construct($folder)
    {
        if (substr($folder, -1) != PATH_SEPARATOR) {
            $folder .= PATH_SEPARATOR;
        }
        do {
            $this->fileName = $folder . uniqid("", true);
        } while (file_exists($this->fileName));
    }

    public function put($data)
    {
        $encoded = json_encode($data);
        if ($encoded === false) {
            throw new Exception("Cannot encode data to json");
        }
        if (file_put_contents($this->fileName, json_encode($data)) === false) {
            throw new Exception("Cannot write to file " . $this->fileName, 1);
        }
    }

    public function get()
    {
        if (($data = file_get_contents($this->fileName)) === false) {
            throw new Exception("Cannot read data from file " . $this->fileName, 2);
        }
        if (unlink($this->fileName) === false) {
            throw new Exception("Cannot delete file " . $this->fileName, 3);
        }
        return json_decode($data, true);
    }
}