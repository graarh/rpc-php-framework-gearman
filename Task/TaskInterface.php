<?php

namespace TaskManager\Task;

interface TaskInterface
{
    public function __construct($config);

    public function getCoreInterface();
    public function worker($params);

    public function run($params);
    public function status();
    public function result();
}
