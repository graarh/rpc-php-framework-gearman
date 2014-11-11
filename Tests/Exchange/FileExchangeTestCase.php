<?php

use ComputationCloud\Exchange\File;

class FileExchangeTestCase extends PHPUnit_Framework_TestCase
{
    public function testPutGet()
    {
        $exchange = new File(['folder' => '.']);
        $exchange->put(['this' => 'is test']);
        $data = $exchange->get();
        $this->assertEquals(['this' => 'is test'], $data, "'test' string was put into exchange");

        $this->setExpectedException("Exception");
        $exchange->get();
    }

    public function testEmptyGet()
    {
        $exchange = new File(['folder' => '.']);
        $this->setExpectedException("Exception");
        $exchange->get();
    }
}