<?php

namespace ComputationCloud\Task;

use ComputationCloud\Helper;
use ComputationCloud\Exchange\ExchangeInterface;
use ComputationCloud\Exchange\Factory;
use ComputationCloud\Json;

/**
 * Class Gearman
 *
 * Config example
 * [
 *  'connection' => ['servers' => '127.0.0.1:4730'],
 *  'function' => 'sum',
 *  'exchange' =>
 *      [
 *          'type' => 'redis',
 *          'config' => ['host' => 'localhost']
 *      ]
 *  ],
 * ]
 *
 *
 * @package ComputationCloud\Task
 */
abstract class Gearman implements TaskInterface
{
    use Json;

    /** @var \GearmanClient|\GearmanWorker $gearmanInstance */
    private $gearmanInstance;
    /** @var ExchangeInterface $exchange */
    private $exchange;
    private $job = null;
    private $name;

    private $result;

    public function __construct(Array $config, $worker = false)
    {
        $servers = Helper::is($config['connection']['servers'], '127.0.0.1:4730');
        $this->name = Helper::is($config['function'], get_class($this));
        if (!$worker) {
            $this->gearmanInstance = new \GearmanClient();
            $this->gearmanInstance->addServers($servers);
        } else {
            $this->gearmanInstance = new \GearmanWorker();
            $this->gearmanInstance->addServers($servers);
            $this->gearmanInstance->addFunction($this->name, [$this, 'workerRunner']);
        }
        if (!($exchangeConfig = Helper::is($config['exchange']))) {
            throw new Exception("Exchange not defined", 1);
        }
        $exchangeFactory = new Factory(Helper::is($exchangeConfig['type']));
        $this->exchange = $exchangeFactory->getInstance(Helper::is($exchangeConfig['config']));
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
        $this->job = $this->gearmanInstance->doBackground(
            $this->name,
            $this->encode(
                [
                    'params' => $params,
                    'exchange' => $this->exchange->getId(),
                ]
            )
        );
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

    public function work()
    {
        $this->gearmanInstance->work();
    }

    public function workerRunner($job)
    {
        $params = $this->decode($job->workload());
        $result = $this->worker($params['params']);
        $this->exchange->setId($params['exchange']);
        $this->exchange->put($result);
        return true;
    }

    abstract public function worker($params);
}