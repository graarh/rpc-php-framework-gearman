<?php

namespace TaskManager\Task;

use TaskManager\TaskInterface;

class AbstractTask implements TaskInterface
{
    public function getCoreInterface()
    {
        // TODO: Implement getCoreInterface() method.
    }

    public function worker($params)
    {
        // TODO: Implement worker() method.
    }

    public function run($params)
    {
        // TODO: Implement run() method.
    }

    public function status()
    {
        // TODO: Implement status() method.
    }

    public function result()
    {
        // TODO: Implement result() method.
    }

    public function __construct($config)
    {
        // TODO: Implement __construct() method.
    }
}