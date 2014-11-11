<?php

namespace ComputationCloud\Exchange;

interface ExchangeInterface
{
    public function put($data);
    public function get();
}
