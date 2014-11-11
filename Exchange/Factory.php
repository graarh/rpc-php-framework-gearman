<?php

namespace ComputationCloud\Exchange;

class Factory
{
    /**
     * @param string $type
     * @param Array $config
     * @return ExchangeInterface
     * @throws Exception
     */
    public function getInstance($type, $config)
    {
        $className = 'ComputationCloud\\Exchange\\'.$type;
        if (!class_exists($className)) {
            throw new Exception("Exchange of type `$type` not exists", 1);
        }
        return new $className($config);
    }
}