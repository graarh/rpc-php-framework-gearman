<?php
namespace ComputationCloud;



interface TaskFactory
{
    public function __construct($config);
    public function getTaskInstance($name);
    public function getMultiTaskInstance($name);
    public function getWorkerInstance($name);
}

