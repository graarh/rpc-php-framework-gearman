<?php

namespace ComputationCloud\Exchange;

use ComputationCloud\Helper;

/**
 * Class Redis
 *
 * Config example:
 * [
 *  'host' => '123.12.1.1',
 *  'port' => '1234',
 *  'timeout' => 5.5,
 * ]
 *
 * @package ComputationCloud\Exchange
 */
class Redis implements ExchangeInterface
{
    use Json;

    /** @var \Redis $redis */
    private $redis;
    private $key;

    public function __construct(Array $config)
    {
        $this->redis = new \Redis();
        $this->redis->connect(
            Helper::is($config['host'], 'localhost'),
            Helper::is($config['port'], 6379),
            Helper::is($config['timeout'], 0.0)
        );

        do {
            $this->key = uniqid("", true);

        } while ($this->redis->exists($this->key));
    }

    public function put($data)
    {
        if (!$this->redis->set($this->key, $this->encode($data))) {
            throw new Exception("Cannot set key `{$this->key}` to redis");
        }
    }

    public function get()
    {
        if (!$this->redis->exists($this->key)) {
            throw new Exception("Key `{$this->key}` not found in redis storage", 1);
        }
        $result = $this->decode($this->redis->get($this->key));
        $this->redis->delete($this->key);
        return $result;
    }
}