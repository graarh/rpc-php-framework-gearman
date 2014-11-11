<?php

namespace TaskManager;

class Helper
{
    static public function is($var, $default = null)
    {
        return isset($var) ? $var : $default;
    }
}