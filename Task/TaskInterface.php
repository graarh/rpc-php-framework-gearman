<?php

namespace TaskManager\Task;

interface TaskInterface
{
    public function __construct($config);

    public function getCoreInterface();
    public function worker($params);

    public function run($params);
    public function isComplete();
    public function result();
}
