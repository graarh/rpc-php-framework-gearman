<?php

use ComputationCloud\Task\Redis;

class MultiSummator extends Redis
{
    public function worker($params)
    {
        return $params['a'] + $params['b'];
    }
}

class MultiTaskTestCase extends PHPUnit_Framework_TestCase
{
    public function testMultiTask()
    {
        $summatorsAmt = 3;
        $config = [
            'connection' => ['host' => 'localhost'],
            'function' => 'sum'.uniqid(),
            'exchange' => [
                'type' => 'Redis',
                'config' => []
            ]
        ];

        $multi = new \ComputationCloud\MultiTask();

        /** @var MultiSummator[] $summators */
        $summators = [];

        //make few summators
        for ($i = 0; $i < $summatorsAmt; $i++) {
            $summator = new MultiSummator($config);
            $summator->run(['a' => 2, 'b' => $i + 1]);
            $summators[] = $summator;
            $multi->addTask($summator);
        }

        //worker should be used in a separate process
        //but for testing purposes let's create it here
        //create worker instance and run it
        $worker = new MultiSummator($config, true);

        //run worker few times to process all summators
        //in real world it can be separate processes
        for ($i = 0; $i < $summatorsAmt; $i++) {
            $this->assertFalse($multi->isComplete(), 'not all tasks were processed');
            $worker->work();
        }

        //check and get result
        $this->assertTrue($multi->isComplete(), 'all tasks are processed');
        for ($i = 0; $i < $summatorsAmt; $i++) {
            $this->assertEquals(3 + $i, $summators[$i]->result(), 'summator failure');
        }
    }
}