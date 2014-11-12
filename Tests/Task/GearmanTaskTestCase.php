<?php

/*
 * WARNING! Run 'RunTestGearmanWorker.php' manually before this test
 */

use ComputationCloud\Task\Gearman;

class GearmanSummator extends Gearman
{
    public function worker($params)
    {
        return $params['a'] + $params['b'];
    }
}

class GearmanTaskTestCase extends PHPUnit_Framework_TestCase
{
    public function testGearmanTask()
    {
        $config = [
            'connection' => ['servers' => '127.0.0.1:4730'],
            'function' => 'sum',
            'exchange' => [
                'type' => 'Redis',
                'config' => ['host' => 'localhost']
            ]
        ];

        $summator = new GearmanSummator($config);
        $summator->run(['a' => 2, 'b' => 2]);

        do {
            sleep(1);
        } while (!$summator->isComplete());

        $this->assertEquals(4, $summator->result(), '2 and 2 was send to summator');
   }
}