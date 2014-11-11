<?php

namespace ComputationCloud\Exchange;

/**
 * Interface ExchangeInterface
 * External exchange, that is not related to current php process
 * @package ComputationCloud\Exchange
 */
interface ExchangeInterface
{
    /**
     * @param array $config
     */
    public function __construct(Array $config);

    /**
     * Put data to this instance of exchange
     * @param $data
     * @throws Exception
     */
    public function put($data);

    /**
     * Get data from this instance of exchange, and remove it
     * @return mixed
     * @throws Exception
     */
    public function pop();

    /**
     * Get id of exchange
     * @return string
     */
    public function getId();

    /**
     * Set id of exchange
     * @param string $id
     */
    public function setId($id);
}
