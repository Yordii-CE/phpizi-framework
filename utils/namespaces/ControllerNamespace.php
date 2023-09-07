<?php

namespace Framework\Utils\Namespaces;

use Framework\Definitions\Interfaces\INamespaceManager;
use Framework\Utils\Hash;
use Framework\Utils\Routing\Path;

class ControllerNamespace implements INamespaceManager
{
    static function  getNamespaceOf($controllerClass)
    {
        $namespace = "";
        foreach (Hash::$controllers as $hash) {
            if (strtolower($hash['class']) == strtolower($controllerClass)) {
                $namespace = $hash['namespace'];
                break;
            }
        }

        return Path::appendIfNotEmpty($namespace, '\\');
    }
}
