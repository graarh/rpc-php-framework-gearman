<?php

use ComputationCloud\Exchange\Factory;

class FactoryExchangeTestCase extends PHPUnit_Framework_TestCase
{
    public function testPutGet()
    {
        $class = (new Factory())->getInstance("File", []);
        $this->assertTrue($class instanceof \ComputationCloud\Exchange\File);
        $class->put("test");
        $this->assertEquals("test", $class->pop());
    }
}