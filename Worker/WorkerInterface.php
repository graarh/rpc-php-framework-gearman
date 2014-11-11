<?php

namespace ComputationCloud\Worker;

use ComputationCloud\Task\TaskInterface;

interface WorkerInterface
{
    public function __construct($config);
    public function work(TaskInterface $task);
}