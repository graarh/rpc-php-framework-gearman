<?php

namespace ComputationCloud\Task;

interface MultiTaskInterface
{
    public function addTask(TaskInterface $task);
    public function isComplete();
}
