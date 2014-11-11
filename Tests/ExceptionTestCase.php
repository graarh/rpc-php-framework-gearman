<?php

class ExceptionTestCase extends PHPUnit_Framework_TestCase
{
    public function testPutGet()
    {
        $exchange = new TaskManager\Exchange\File("/tmp");
        $exchange->put("test");
        $data = $exchange->get();
        $this->assertEquals("test", $data, "'test' string was put into exchange");

        $this->setExpectedException("TaskManager\\Exchange\\Exception");
        $exchange->get();
    }

    public function testEmptyGet()
    {
        $exchange = new TaskManager\Exchange\File("/tmp");
        $this->setExpectedException("TaskManager\\Exchange\\Exception");
        $exchange->get();
    }
}