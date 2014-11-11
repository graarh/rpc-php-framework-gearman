<?php

namespace ComputationCloud\Task;

class GearmanMulti implements MultiTaskInterface
{
    /** @var []TaskInterface $tasks */
    private $tasks = [];

    public function addTask(TaskInterface $task)
    {
        $this->tasks[] = $task;
    }

    public function isComplete()
    {
        foreach ($this->tasks as $task) {
            /** @var TaskInterface $task */

            if (!$task-isComplete()) {
                return false;
            }
        }
        return true;
    }
}