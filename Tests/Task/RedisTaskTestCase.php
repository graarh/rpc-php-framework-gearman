<?php

use ComputationCloud\Task\Redis;

class Summator extends Redis
{
    public function worker($params)
    {
        return $params['a'] + $params['b'];
    }
}

class RedisTaskTestCase extends PHPUnit_Framework_TestCase
{
    public function testRedisTask()
    {
        $config = [
            'connection' => ['host' => 'localhost'],
            'function' => 'sum'.uniqid(),
            'exchange' => [
                'type' => 'Redis',
                'config' => []
            ]
        ];

        //run task, or 'client'
        $summator = new Summator($config);

        //not running task should be complete
        $this->assertTrue($summator->isComplete());
        $summator->run(['a' => 2, 'b' => 2]);
        //now task is waiting
        $this->assertFalse($summator->isComplete());

        //worker should be used in a separate process
        //but for testing purposes let's create it here
        //create worker instance and run it
        $worker = new Summator($config, true);
        $worker->work();

        //check and get result
        $this->assertTrue($summator->isComplete());
        $this->assertEquals(4, $summator->result());
    }
}