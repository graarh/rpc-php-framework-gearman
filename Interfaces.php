<?php
namespace TaskManager;


interface TaskInterface
{
    //config
    public function __construct($config);

    //worker part
    public function getCoreInterface();
    public function worker($params);

    //client part
    public function run($params);
    public function status();
    public function result();
}

interface TaskFactory
{
    public function __construct($config);
    public function getTaskInstance($name);
    public function getWorkerInstance($name);
}

interface MultiTaskInterface
{
    public function addTask(TaskInterface $task);
    public function status();
}

interface WorkerInterface
{
    public function __construct($config);
    public function work(TaskInterface $task);
}