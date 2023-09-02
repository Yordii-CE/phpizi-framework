<?php

//getRouteTo debe recibir los aprasmetros string: 4/5/ y no array

class Router
{
    static function getRouteTo($controllerName, $actionName, $paramsString)
    {

        //Reflection
        $controllerClassName = $controllerName . 'Controller';

        $controllerReflection = new ControllerReflectionUtils($controllerClassName);
        $actionReflection = new ActionReflectionUtils($controllerClassName, $actionName);


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
