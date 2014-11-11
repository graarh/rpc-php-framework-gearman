<?php

use ComputationCloud\Exchange\Redis;

class RedisExchangeTestCase extends PHPUnit_Framework_TestCase
{
    public function testPutGet()
    {
        $exchange = new Redis(['host' => 'localhost']);
        $exchange->put(['this' => 'is test']);
        $data = $exchange->pop();
        $this->assertEquals(['this' => 'is test'], $data, "'test' string was put into exchange");

        $this->setExpectedException("Exception");
        $exchange->pop();
    }

    public function testEmptyGet()
    {
        $exchange = new Redis(['host' => 'localhost']);
        $this->setExpectedException("Exception");
        $exchange->pop();
    }
}