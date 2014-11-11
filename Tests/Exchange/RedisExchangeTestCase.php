<?php

use ComputationCloud\Exchange\Redis;

class RedisExchangeTestCase extends PHPUnit_Framework_TestCase
{
    public function testPutGet()
    {
        $client = new \Redis();
        $client->connect("localhost");

        $exchange = new Redis($client);
        $exchange->put(['this' => 'is test']);
        $data = $exchange->get();
        $this->assertEquals(['this' => 'is test'], $data, "'test' string was put into exchange");

        $this->setExpectedException("Exception");
        $exchange->get();
    }

    public function testEmptyGet()
    {
        $client = new \Redis();
        $client->connect("localhost");

        $exchange = new Redis($client);
        $this->setExpectedException("Exception");
        $exchange->get();
    }
}