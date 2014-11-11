<?php

namespace ComputationCloud;

use ComputationCloud\Task\TaskInterface;

class MultiTask
{
    /** @var TaskInterface[] $tasks */
    private $tasks = [];

    public function addTask(TaskInterface $task)
    {
        $this->tasks[] = $task;
    }

    public function isComplete()
    {
        foreach ($this->tasks as $task) {
            /** @var TaskInterface $task */
            if (!$task->isComplete()) {
                return false;
            }
        }
        return true;
    }
}