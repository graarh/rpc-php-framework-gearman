<?php

namespace ComputationCloud;

use ComputationCloud\Task\TaskInterface;

class MultiTask
{
    /** @var TaskInterface[] $tasks */
    private $tasks = [];
    private $completed = [];

    /**
     * @param TaskInterface[] $tasks
     */
    public function __construct(Array $tasks = [])
    {
        $this->tasks = $tasks;
    }

    /**
     * Add task
     * @param TaskInterface $task
     */
    public function addTask(TaskInterface $task)
    {
        $this->tasks[] = $task;
    }

    /**
     * Check that all tasks are complete
     * @return bool
     */
    public function isComplete()
    {
        foreach ($this->tasks as $idx => $task) {
            if (in_array($idx, $this->completed)) {
                continue;
            }
            /** @var TaskInterface $task */
            if (!$task->isComplete()) {
                return false;
            }
            $this->completed[] = $idx;
        }
        return true;
    }

    /**
     * Get all results
     * @return array|bool
     */
    public function getResults()
    {
        if (!$this->isComplete()) {
            return false;
        }
        $result = [];
        foreach ($this->tasks as $task) {
            $result[] = $task->result();
        }
        return $result;
    }
}