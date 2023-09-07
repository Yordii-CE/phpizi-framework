<?php

namespace Framework\Utils\Namespaces;

class HelpersNamespaces
{
    public static function getClass($namespaceWithClass)
    {
        $parts = explode('\\', $namespaceWithClass);
        $class = end($parts);

        return $class;
    }

    public static function convertToPath($namespace)
    {
        return str_replace("\\", "/", $namespace) . ".php";
    }
}
