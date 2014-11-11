<?php

namespace TaskManager\Exchange;

interface ExchangeInterface
{
    public function put($data);
    public function get();
}
