<?php

namespace ComputationCloud\Task;

use ComputationCloud\Helper;
use ComputationCloud\Exchange\ExchangeInterface;
use ComputationCloud\Json;

/**
 * Class Redis
 *
 * Redis tasks has no "crashed" tasks protection
 *
 * Config example
 * [
 *  'connection' => ['server' => 'localhost'],
 *  'function' => 'sum',
 *  'exchange' => new ComputationCloud\Exchange\Redis(...)
 *  ],
 * ]
 *
 * @package ComputationCloud\Task
 */
abstract class Redis implements TaskInterface
{
    use Json;

    /** @var \Redis $redisInstance */
    private $redisInstance;
    /** @var ExchangeInterface $exchange */
    private $exchange;

    private $key;
    private $name;
    private $result = null;

    private function getCreatedTasksSet()
    {
        return $this->name . '-created';
    }

    public function __construct(Array $config, $worker = false)
    {
        $server = Helper::is($config['connection']['server'], ['host' => 'localhost']);
        $this->redisInstance = new \Redis();
        $this->redisInstance->connect(
            Helper::is($server['host'], 'localhost'),
            Helper::is($server['port'], 6379),
            Helper::is($server['timeout'], 0.0)
        );

        $this->name = Helper::is($config['function'], get_class($this));

        if ($worker === false) {
            do {
                $this->key = $this->name . '-' . uniqid('', true);
            } while ($this->redisInstance->exists($this->key));
        }

        if (!($this->exchange = Helper::is($config['exchange']))) {
            throw new Exception("Exchange not defined", 1);
        }
    }

    public function getCoreInterface()
    {
        return $this->redisInstance;
    }

    public function run($params)
    {
        if (!$this->isComplete()) {
            throw new Exception("Do not run another task on the same instance until current is working", 1);
        }
        $this->result = null;

        $data = [
            'params' => $params,
            'time' => time(),
        ];

        if (!$this->redisInstance->set($this->key, $this->encode($data))) {
            throw new Exception("Cannot set key '{$this->key}' to redis");
        }
        if (!$this->redisInstance->sAdd($this->getCreatedTasksSet(), $this->key)) {
            throw new Exception("Task was not added to tasks set `$this->key` to redis");
        }
    }

    public function isComplete()
    {
        return !$this->redisInstance->exists($this->key);
    }

    public function result()
    {
        if (!is_null($this->result)) {
            return $this->result;
        }
        if (!$this->isComplete()) {
            return false;
        }
        $this->result = $this->exchange->pop();
        $this->redisInstance->delete($this->key);
        return $this->result;
    }

    public function work()
    {
        $jobName = $this->redisInstance->sPop($this->getCreatedTasksSet());
        if (!$this->redisInstance->exists($jobName)) {
            throw new Exception("Job not found, but present in queue", 1);
        }
        $data = $this->decode($this->redisInstance->get($jobName));
        $result = $this->worker($data['params']);
        $this->exchange->put($result);
        $this->redisInstance->delete($jobName);
    }

    abstract public function worker($params);
}