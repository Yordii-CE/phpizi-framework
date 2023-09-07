<?php

namespace Framework\Utils\Namespaces;

use Framework\Definitions\Interfaces\INamespaceManager;

class MiddlewareNamespace implements INamespaceManager
{
    static function  getNamespaceOf($middlewareClass)
    {
        $namespace = 'App\\Middlewares\\';

        return $namespace;
    }
}
