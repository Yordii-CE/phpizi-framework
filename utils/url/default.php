<?php

class DefaultUrl
{
    public static $pattern;
    public static $defaultPrefix = null;
    static function getUrl(): UrlComponent
    {

        $controllerPrefix = null;
        $controller = null;
        $actionPrefix = null;
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



        // Check prefix      
        $controllerClassName = $controller . 'Controller';
        $controllerReflection = new ControllerReflectionUtils($controllerClassName);

        $actionReflection = new ActionReflectionUtils($controllerClassName, $action);

        $controllerPrefix = $controllerReflection->getPrefix();
        $actionPrefix = $actionReflection->getPrefix();

        return new UrlComponent(DefaultUrl::$defaultPrefix, $controllerPrefix, $controller, $actionPrefix, $action, $params);

        /*return [
            'controller_prefix' => $controllerPrefix,
            'controller' => $controller,
            'action_prefix' => $actionPrefix,
            'action' => $action,
            'params' => $params
        ];*/
    }

    public static function validatePatternFormat(): bool
    {
        //$pattern = '/^(?:[a-zA-Z]+(?:\/[a-zA-Z]+)*\/)?(?:\{[a-zA-Z0-9_-]+\}\/)+\{[a-zA-Z0-9_,-]+\}$/';
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
