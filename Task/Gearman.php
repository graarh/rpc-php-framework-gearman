<?php

namespace TaskManager\Task;

use TaskManager\TaskInterface;
use TaskManager\Helper;
use TaskManager\Exchange;

abstract class Gearman implements TaskInterface
{
    /** @var \GearmanWorker $gearmanInstance*/
    private $gearmanInstance;
    /** @var Exchange $exchange */
    private $exchange;

    public function __construct($config)
    {
        $servers = Helper::is($config['gearman']['servers'], '127.0.0.1:4730');
        $this->gearmanInstance = new \GearmanWorker();
        $this->gearmanInstance->addServers($servers);

        $funcName = Helper::is($config['gearman']['function'], get_class($this));
        $this->gearmanInstance->addFunction($funcName, [$this, 'work']);

        if (!($this->exchange = Helper::is($config['exchange']))) {
            throw new Exception("Exchange not defined", 1);
        }
    }

    public function getCoreInterface()
    {
        return $this->gearmanInstance;
    }

    public function run($params)
    {
        // TODO: Implement run() method.
    }

    public function status()
    {
        // TODO: Implement status() method.
    }

    public function result()
    {
        // TODO: Implement result() method.
    }

    abstract public function worker($params);
}