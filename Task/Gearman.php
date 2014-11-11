<?php

namespace TaskManager\Task;

use TaskManager\TaskInterface;
use TaskManager\Helper;
use TaskManager\Exchange;

abstract class Gearman implements TaskInterface
{
    /** @var \GearmanClient $gearmanInstance*/
    private $gearmanInstance;
    /** @var Exchange $exchange */
    private $exchange;
    private $job = null;
    private $name;

    public function __construct($config, $worker = false)
    {
        if (!$worker) {
            $servers = Helper::is($config['gearman']['servers'], '127.0.0.1:4730');
            $this->gearmanInstance = new \GearmanClient();
            $this->gearmanInstance->addServers($servers);

            $this->name = Helper::is($config['gearman']['function'], get_class($this));

            if (!($this->exchange = Helper::is($config['exchange']))) {
                throw new Exception("Exchange not defined", 1);
            }
        }
    }

    public function getCoreInterface()
    {
        return $this->gearmanInstance;
    }

    public function run($params)
    {
        $this->job = $this->gearmanInstance->doBackground($this->name, json_encode($params));
    }

    public function status()
    {
        if (!$this->job) {
            return false;
        }
        return $this->gearmanInstance->jobStatus($this->job);
    }

    public function result()
    {
        if (!$this->job || !$this->status()[0]) {
            return false;
        }
        return $this->exchange->get();
    }

    abstract public function worker($params);
}