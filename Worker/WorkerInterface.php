<?php

namespace TaskManager\Worker;

use TaskManager\Task\TaskInterface;

interface WorkerInterface
{
    public function __construct($config);
    public function work(TaskInterface $task);
}