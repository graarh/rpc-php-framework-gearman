<?php

namespace ComputationCloud\Task;

use ComputationCloud\Helper;
use ComputationCloud\Exchange\ExchangeInterface;

abstract class Gearman implements TaskInterface
{
    /** @var \GearmanClient|\GearmanWorker $gearmanInstance*/
    private $gearmanInstance;
    /** @var ExchangeInterface $exchange */
    private $exchange;
    private $job = null;
    private $name;

    private $result;

    public function __construct(Array $config, $worker = false)
    {
        $servers = Helper::is($config['gearman']['servers'], '127.0.0.1:4730');
        $this->name = Helper::is($config['gearman']['function'], get_class($this));
        if (!$worker) {
            $this->gearmanInstance = new \GearmanClient();
            $this->gearmanInstance->addServers($servers);
        } else {
            $this->gearmanInstance = new \GearmanWorker();
            $this->gearmanInstance->addFunction($this->name, [$this, 'workerRunner']);
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
        $this->result = null;
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
        if (!is_null($this->result)) {
            return $this->result;
        }
        if (!$this->job || !$this->isComplete()) {
            return false;
        }
        $this->result = $this->exchange->pop();
        return $this->result;
    }

    public function work() {
        $this->gearmanInstance->work();
    }

    public function workerRunner($job) {
        $params = json_decode($job->workload(), true);
        $result = $this->worker($params);
        $this->exchange->put($result);
        return true;
    }

    abstract public function worker($params);
}