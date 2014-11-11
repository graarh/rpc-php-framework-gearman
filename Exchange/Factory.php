<?php

namespace ComputationCloud\Exchange;

class Factory
{
    public function getInstance($type, $config)
    {
        $className = 'ComputationCloud/Exchange/'.$type;
        if (!class_exists($className)) {
            throw new Exception("Extension of type `$type` not exists", 1);
        }
        return new $className($config);
    }
}