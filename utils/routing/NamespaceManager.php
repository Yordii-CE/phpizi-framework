<?php

namespace Framework\Utils\Routing;

class NamespaceManager
{
    //App
    public static $controllers = 'App\\Controllers\\';
    public static $middlewares = 'App\\Middlewares\\';

    //Framework
    public static $abstracts = 'Framework\\Definitions\\Abstracts\\';

    static function removeControllerNamespace($controllerClass)
    {
        return str_replace(NamespaceManager::$controllers, "", $controllerClass);
    }
    static function removeMiddlewareNamespace($middlewareClass)
    {
        return str_replace(NamespaceManager::$middlewares, "", $middlewareClass);
    }
    static function getOnlyClass($namespaceWithClass)
    {
        $parts = explode('\\', $namespaceWithClass);
        return end($parts);
    }
}
