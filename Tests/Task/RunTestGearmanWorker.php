<?php

require(__DIR__."/../../bootstrap.php");

use ComputationCloud\Task\Gearman;

class GearmanSummatorTest extends Gearman
{
    public function worker($params)
    {
        return $params['a'] + $params['b'];
    }
}

$config = [
    'connection' => ['servers' => '127.0.0.1:4730'],
    'function' => 'sum',
    'exchange' => [
        'type' => 'Redis',
        'config' => ['host' => 'localhost']
    ]
];

//run server in background
$worker = new GearmanSummatorTest($config, true);
$worker->work();
