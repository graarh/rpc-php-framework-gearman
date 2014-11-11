<?php

namespace ComputationCloud\Exchange;

use ComputationCloud\Helper;
use ComputationCloud\Json;

class File implements ExchangeInterface
{
    use Json;

    private $fileName;

    public function __construct(Array $config)
    {
        $folder = Helper::is($config['folder'], '.');
        if (substr($folder, -1) != DIRECTORY_SEPARATOR) {
            $folder .= DIRECTORY_SEPARATOR;
        }
        if ($fileName = Helper::is($config['id'])) {
            $this->fileName = $fileName;
        } else {
            do {
                $this->fileName = $folder . uniqid("", true);
            } while (file_exists($this->fileName));
        }
    }

    public function put($data)
    {
        if (file_put_contents($this->fileName, $this->encode($data)) === false) {
            throw new Exception("Cannot write to file " . $this->fileName, 1);
        }
    }

    public function pop()
    {
        if (($data = file_get_contents($this->fileName)) === false) {
            throw new Exception("Cannot read data from file " . $this->fileName, 1);
        }
        if (unlink($this->fileName) === false) {
            throw new Exception("Cannot delete file " . $this->fileName, 1);
        }
        return $this->decode($data);
    }

    public function getId()
    {
        return $this->fileName;
    }
}