<?php

namespace ComputationCloud\Task;

use ComputationCloud\Helper;
use ComputationCloud\Exchange\ExchangeInterface;

abstract class Gearman implements TaskInterface
{
    /** @var \GearmanClient $gearmanInstance*/
    private $gearmanInstance;
    /** @var ExchangeInterface $exchange */
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
        }
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
        if (!$this->isComplete()) {
            throw new Exception("Do not run another task on the same instance until current is working", 1);
        }
        $this->job = $this->gearmanInstance->doBackground($this->name, json_encode($params));
    }

    public function isComplete()
    {
        if (!$this->job) {
            return true;
        }
        return !$this->gearmanInstance->jobStatus($this->job)[0];
    }

    public function result()
    {
        if (!$this->job || !$this->isComplete()) {
            return false;
        }
        return $this->exchange->get();
    }

    abstract public function worker($params);
}