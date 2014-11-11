<?php

class FileExchangeTestCase extends PHPUnit_Framework_TestCase
{
    public function testPutGet()
    {
        $exchange = new ComputationCloud\Exchange\File("/tmp");
        $exchange->put("test");
        $data = $exchange->get();
        $this->assertEquals("test", $data, "'test' string was put into exchange");

        $this->setExpectedExceptComputationCloudnager\\Exchange\\Exception");
        $exchange->get();
    }

    public function testEmptyGet()
    {
        $exchaComputationCloudaskManager\Exchange\File("/tmp");
        $this->setExpeComputationCloudon("TaskManager\\Exchange\\Exception");
        $exchange->get();
    }
}