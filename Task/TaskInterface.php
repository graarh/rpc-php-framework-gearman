<?php

namespace ComputationCloud\Task;

interface TaskInterface
{
    /**
     * @param array $config
     * @param bool $worker
     */
    public function __construct(Array $config, $worker = false);

    /**
     * Get instance of manager, that this tasks using
     * E.g. redis or gearman
     * @return mixed
     */
    public function getCoreInterface();

    /**
     * Worker implementation
     * @param array $params
     * @return array
     */
    public function worker($params);

    /**
     * Run instance of worker with given parameters
     * @param array $params
     * @return array
     */
    public function run($params);

    /**
     * Is worker finished?
     * @return bool
     */
    public function isComplete();

    /**
     * Get worker result
     * @return array
     */
    public function result();

    /**
     * Run worker instance
     */
    public function work();
}
