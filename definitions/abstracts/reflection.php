<?php
abstract class ReflectionUtils
{
    public Object $reflection;
    function __construct(String $className, $actionName = null)
    {
        if ($actionName === null) {
            $this->reflection = new ReflectionClass($className);
        } else {
            $this->reflection = new ReflectionMethod($className, $actionName);
        }
    }
}
