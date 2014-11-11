<?php
namespace TaskManager;

interface Exchange
{
    public function put($data);
    public function get();
}

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
}

interface MultiTask
{
    public function addTask(TaskInterface $task);
    public function status();
}