<?php

namespace Framework\Definitions\Abstracts;

abstract class ReflectionUtils
{
    public Object $reflection;
    function __construct(String $class, $action = null)
    {
        if ($action === null) {
            $this->reflection = new \ReflectionClass($class);
        } else {
            $this->reflection = new \ReflectionMethod($class, $action);
        }
    }
}
