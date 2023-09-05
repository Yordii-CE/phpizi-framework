<?php

namespace Framework\Utils\Reflection;

use Framework\Definitions\Abstracts\ReflectionUtils;

class ModelReflection extends ReflectionUtils
{
    function __construct(String $controllerClassName)
    {
        parent::__construct($controllerClassName, null);
    }

    function getConstructorDatabases()
    {
        $constructor = $this->reflection->getConstructor();
        $conections = [];

        if ($constructor) {
            $params = $constructor->getParameters();

            foreach ($params as $param) {
                array_push($conections, [
                    "name" => $param->getName(),
                    "type" => ($param->hasType() ? $param->getType()->getName() : "")

                ]);
            }
        }
        return $conections;
    }
}
