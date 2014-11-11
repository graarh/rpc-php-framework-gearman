<?php

namespace ComputationCloud\Exchange;

class Factory
{
    private $className;

    /**
     * @param string $type
     * @throws Exception
     */
    public function __construct($type)
    {
        $className = 'ComputationCloud\\Exchange\\'.$type;
        if (!class_exists($className)) {
            throw new Exception("Exchange of type `$type` not exists", 1);
        }
        $this->className = $className;
    }
    /**
     * @param Array $config
     * @return ExchangeInterface
     * @throws Exception
     */
    public function getInstance($config)
    {
        $className = $this->className;
        return new $className($config);
    }
}