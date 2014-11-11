<?php

namespace ComputationCloud;

class JsonException extends \Exception
{
}


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
     * @throws JsonException
     */
    public function encode($data)
    {
        $encoded = json_encode($data);
        if ($encoded === false) {
            throw new JsonException("Cannot encode data to json", 1);
        }
        return $encoded;
    }

    /**
     * Decode from json to array
     * @param $data
     * @return mixed
     * @throws JsonException
     */
    public function decode($data)
    {
        $decoded = json_decode($data, true);
        if ($decoded === false) {
            throw new JsonException("Cannot decode data from json", 1);
        }
        return $decoded;
    }
}