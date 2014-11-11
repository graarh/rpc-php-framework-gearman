<?php

namespace ComputationCloud\Task;

use ComputationCloud\Helper;
use ComputationCloud\Exchange\ExchangeInterface;
use ComputationCloud\Json;

abstract class Redis implements TaskInterface
{
    use Json;

    /** @var \Redis $redisInstance */
    private $redisInstance;
    /** @var ExchangeInterface $exchange */
    private $exchange;

    private $key;
    private $name;

    public function __construct($config, $worker = false)
    {
        if (!$worker) {
            $server = Helper::is($config['redis']['server'], ['host' => 'localhost']);
            $this->redisInstance = new \Redis();
            $this->redisInstance->connect(
                Helper::is($server['host'], 'localhost'),
                Helper::is($server['port'], 6379),
                Helper::is($server['timeout'], 0.0)
            );

            $this->name = Helper::is($config['redis']['function'], get_class($this));

            do {
                $this->key = $this->name.'-'.uniqid('', true);
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

        $data = [
            'params' => $params,
            'status' => 'created',
            'time' => time(),
        ];

        if (!$this->redisInstance->set($this->key, $this->encode($data))) {
            throw new Exception("Cannot set key '{$this->key}' to redis");
        }
        if (!$this->redisInstance->sAdd($this->name, $this->key)) {
            throw new Exception("Task was not added to tasks set `$this->key` to redis");
        }
    }

    public function isComplete()
    {
        if (!$this->redisInstance->exists($this->key)) {
            return true;
        }
        $status = Helper::is($this->decode($this->redisInstance->get($this->key)), 'status');
        if ($status !== 'complete') {
            return false;
        }
        return true;
    }

    public function result()
    {
        if (!$this->isComplete()) {
            return false;
        }
        return $this->exchange->get();
    }

    abstract public function worker($params);
}