<?php

namespace ComputationCloud\Exchange;

/**
 * json decode, encode with exceptions on failure
 * @package ComputationCloud\Exchange
 */
trait Json
{
    /**
     * Encode to json
     * @param $data
     * @return string
     * @throws Exception
     */
    public function encode($data)
    {
        $encoded = json_encode($data);
        if ($encoded === false) {
            throw new Exception("Cannot encode data to json", 1);
        }
        return $encoded;
    }

    /**
     * Decode from json to array
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function decode($data)
    {
        $decoded = json_decode($data, true);
        if ($decoded === false) {
            throw new Exception("Cannot decode data from json", 1);
        }
        return $decoded;
    }
}