<?php

namespace Framework\Utils;

class Hash
{
    public static $controllers = [];
    public static function createControllerHash($controllers)
    {
        foreach ($controllers as $controller) {
            $parts = explode('\\', $controller);
            $namespace =  implode('\\', array_slice($parts, 0, -1));
            $class = end($parts);

            array_push(Hash::$controllers, [
                "namespace" => $namespace,
                "class" => $class
            ]);
        }
    }
}
