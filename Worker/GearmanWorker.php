<?php

namespace TaskManager\Worker;

use TaskManager\Helper;
use TaskManager\WorkerInterface;
use TaskManager\Task\TaskInterface;
use TaskManager\Exchange\ExchangeInterface;

class GearmanWorker implements WorkerInterface
{
    /** @var \GearmanWorker $gearmanInstance */
    private $gearmanInstance;
    /** @var ExchangeInterface $exchange */
    private $exchange;
    /** @var TaskInterface $task */
    private $task;

    public function __construct($config)
    {
        $servers = Helper::is($config['gearman']['servers'], '127.0.0.1:4730');
        $this->gearmanInstance = new \GearmanWorker();
        $this->gearmanInstance->addServers($servers);

        if (!($name = Helper::is($config['gearman']['function']))) {
            throw new Exception("Gearman function name is not set", 10);
        }
        $this->gearmanInstance->addFunction($name, [$this, 'worker']);

        if (!($this->exchange = Helper::is($config['exchange']))) {
            throw new Exception("Exchange not defined", 1);
        }
    }

    public function work(TaskInterface $task)
    {
        $this->task = $task;
        $this->gearmanInstance->work();
    }

    public function worker($job) {
        $params = json_decode($job->workload(), true);
        $result = $this->task->worker($params);
        $this->exchange->put($result);
        return true;
    }
}