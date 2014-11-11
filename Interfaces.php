<?php
namespace TaskManager;



interface TaskFactory
{
    public function __construct($config);
    public function getTaskInstance($name);
    public function getWorkerInstance($name);
}

interface MultiTaskInterface
{
    public function addTask(Task\TaskInterface $task);
    public function status();
}

interface WorkerInterface
{
    public function __construct($config);
    public function work(Task\TaskInterface $task);
}