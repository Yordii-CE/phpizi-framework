<?php

namespace Framework\Utils\Url;

use Framework\Utils\Reflection\ControllerReflection;
use Framework\Utils\Reflection\ActionReflection;
use Framework\Utils\Routing\NamespaceManager;


class DefaultUrl
{
    public static $pattern;

    //App prefix
    public static $defaultPrefix = null;

    //Devuelve las partes de la url default
    static function getParts()
    {
        $controller = null;
        $action = null;
        $params = [];

        // Buscar el índice de inicio de los parámetros
        $paramsStartIndex = strpos(DefaultUrl::$pattern, '{');

        // Extraer el prefix si está presente        
        if ($paramsStartIndex > 0) {
            $defaultPrefix = substr(DefaultUrl::$pattern, 0, $paramsStartIndex);
            $defaultPrefix = trim($defaultPrefix, '/');

            DefaultUrl::$defaultPrefix = $defaultPrefix;
        }

        // Extraer la parte de la URL que contiene los elementos después del prefix
        $urlPart = substr(DefaultUrl::$pattern, $paramsStartIndex);

        // Obtener el índice de cierre de los parámetros
        $paramsEndIndex = strrpos($urlPart, '}');

        // Extraer la parte de la URL que contiene los elementos de controller, action y params
        $elementsPart = substr($urlPart, 0, $paramsEndIndex + 1);

        // Remover las llaves de controller y action
        $elementsPart = str_replace(['{', '}'], '', $elementsPart);

        // Dividir los elementos en un array
        $elements = explode('/', $elementsPart);

        // Asignar los elementos a las variables correspondientes
        if (count($elements) > 0) $controller = $elements[0];

        if (count($elements) > 1) $action = $elements[1];

        if (count($elements) > 2) $params = explode(',', $elements[2]);

        return [
            "controller" => $controller,
            "action" => $action,
            "params" => $params
        ];
    }
    static function getComponents(): UrlComponent
    {
        $parts = DefaultUrl::getParts();

        $controller = $parts['controller'];
        $action = $parts['action'];
        $params = [];

        $controllerPrefix = null;
        $actionPrefix = null;

        //Get prefix
        $controllerReflection = new ControllerReflection(NamespaceManager::$controllers . $controller);
        $actionReflection = new ActionReflection(NamespaceManager::$controllers . $controller, $action);

        $controllerPrefix = $controllerReflection->getPrefix();
        $actionPrefix = $actionReflection->getPrefix();

        return new UrlComponent(DefaultUrl::$defaultPrefix, $controllerPrefix, $controller, $actionPrefix, $action, $params);
    }

    public static function validatePatternFormat(): bool
    {
        $expression = '/^(?:[\w]+(?:\/[\w]+)*\/)?(?:\{[\w]+\}\/)+\{[\w]+(?:,[\w]+)*\}$/';


        $matchCount = preg_match($expression, DefaultUrl::$pattern);

        if ($matchCount !== 1) return false;

        if (substr_count(DefaultUrl::$pattern, '{') > 3) return false;

        $paramsStartIndex = strrpos(DefaultUrl::$pattern, '{');
        $paramsEndIndex = strpos(DefaultUrl::$pattern, '}', $paramsStartIndex);
        $paramsPart = substr(DefaultUrl::$pattern, $paramsEndIndex + 1);

        return trim($paramsPart) === '';
    }
}
