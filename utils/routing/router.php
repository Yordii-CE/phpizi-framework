<?php

namespace Framework\Utils\Routing;

use Framework\Utils\Reflection\ControllerReflection;
use Framework\Utils\Reflection\ActionReflection;
use Framework\Utils\Url\DefaultUrl;
use Framework\Utils\Namespaces\ControllerNamespace;

class Router
{
    static function getRouteTo($controllerName, $actionName, $paramsString)
    {
        //Reflection        
        $controllerNamespace = ControllerNamespace::getNamespaceOf($controllerName);

        $controllerReflection = new ControllerReflection($controllerNamespace . $controllerName);
        $actionReflection = new ActionReflection($controllerNamespace . $controllerName, $actionName);


        $controllerPrefix = $controllerReflection->getPrefix();
        $actionPrefix = $actionReflection->getPrefix();

        // Variables     
        $projectPath = Path::getProjectPath() . '/';
        $defaultPrefix = Path::appendIfNotEmpty(DefaultUrl::$defaultPrefix, '/');
        $controllerPrefix = Path::appendIfNotEmpty($controllerPrefix, '/');
        $controllerName = strtolower($controllerName) . '/';
        $actionPrefix = Path::appendIfNotEmpty($actionPrefix, '/');

        //Si hay parametros pero actionName es '', establecerle 'index' para poder colocar los parametros luego
        if (!empty($paramsString) && empty($actionName)) $actionName = 'index';

        $route = $projectPath . $defaultPrefix . $controllerPrefix . $controllerName . $actionPrefix . $actionName;

        //add params
        $route = $route . '/' . $paramsString;
        $route = rtrim($route, '/');     //Ninguna ruta debe terminar con '/'   

        return $route;
    }
}
