<?php

class InputUrl
{

    public static $inputPrefix = null;

    //Added 2 params For api
    public static function getUrl($actionPlaceholder = null)
    {
        $url = $_GET['url'] ?? '';

        if ($actionPlaceholder !== null) {
            //Nos aseguramos que la url tenga al final un '/'
            $url = trim($url, '/');
            $url = $url . '/';

            //Buscamos la ruta y despues inyectamos el actionName

            //$controller and $actionPrefix            
            $route = $actionPlaceholder['controller'] . '/' . Path::appendIfNotEmpty($actionPlaceholder['prefix'], '/');

            $route = preg_quote($route, '/');

            $actionName = $actionPlaceholder['name'] . '/';

            $url = preg_replace("/($route)/", "$0" . $actionName, $url, 1);
        }

        $url = trim($url, '/');
        $url = InputUrl::splitUrl($url);

        $controllerPrefix = null;
        $controller = null;
        $actionPrefix = null;
        $action = null;
        $params = [];

        if (!empty($url)) {

            //AppPrefix
            $urlString = implode("/", $url) . '/';

            if (strpos($urlString, DefaultUrl::$defaultPrefix . '/') === 0) {
                //Si hay prefijo de app en url
                InputUrl::$inputPrefix = DefaultUrl::$defaultPrefix;

                // Quitamos appPrefix del array $url.                    
                $urlWithoutAppPrefix = str_replace(DefaultUrl::$defaultPrefix . "/", "", $urlString);

                $url = InputUrl::splitUrl($urlWithoutAppPrefix);
            }

            //Controller
            $controllerIndex = InputUrl::findControllerIndexInUrl($url);
            if ($controllerIndex !== null) {

                $controller = $url[$controllerIndex] ?? null;

                //ControllerPrefix
                $controllerPrefix = InputUrl::getControllerPrefix($url, $controllerIndex);

                //Action
                $actionIndex = InputUrl::findActionIndexInUrl($url, $controllerIndex);
                if ($actionIndex !== null) {
                    $action = $url[$actionIndex] ?? null;


                    //ActionPrefix
                    $actionPrefix = InputUrl::getActionPrefix($url, $controllerIndex, $actionIndex);
                } else {
                    //Entonces no es action, todo el $url desde $controllerIndex + 1 hasta el final sera actionPrefix
                    $actionPrefix = implode('/', array_slice($url, $controllerIndex + 1)) ?? null;
                }
                //Params                

                $lenActionPrefix = empty($actionPrefix) ? 0 : count(explode('/', $actionPrefix));
                $startIndex = $controllerIndex + $lenActionPrefix;

                $params = InputUrl::getParams($url, $startIndex + 1);
            } else {
                //Entonces no es controller, todo el $url sera controllerPrefix
                $urlString = implode('/', $url);
                $urlString = trim($urlString, '/');
                $controllerPrefix =  $urlString;
            }
        }

        return new UrlComponent(InputUrl::$inputPrefix, $controllerPrefix, $controller, $actionPrefix, $action, $params);
    }

    public static function parseUrl($inputUrl, $defaultUrl): UrlComponent
    {
        $controller = isset($_GET['url']) ? $inputUrl->controller ?? $defaultUrl->controller : $defaultUrl->controller;
        $actionPrefix = isset($_GET['url']) ? $inputUrl->actionPrefix ?? $defaultUrl->actionPrefix : $defaultUrl->actionPrefix;
        $action = isset($_GET['url']) ? $inputUrl->action ?? $defaultUrl->action : $defaultUrl->action;
        $params = (!isset($_GET['url']) || empty($inputUrl->params)) && $inputUrl->controller == $defaultUrl->controller ? $defaultUrl->params : $inputUrl->params;

        return new UrlComponent($inputUrl->appPrefix, $inputUrl->controllerPrefix, $controller, $actionPrefix, $action, $params);
    }


    private static function splitUrl($url): array
    {
        if ($url == '') return [];
        $url = rtrim($url, '/');
        $url = explode('/', $url);
        return $url;
    }

    public static function findControllerIndexInUrl($url): ?int
    {
        $controllerIndex = null;
        for ($i = 0; $i < count($url); $i++) {
            if (class_exists($url[$i] . "Controller")) {
                $controllerIndex = $i;
                break;
            }
        }
        return $controllerIndex;
    }

    public static function findActionIndexInUrl($url, $controllerIndex): ?int
    {

        $controllerClassName = $url[$controllerIndex] . "Controller";
        $actionIndex = null;
        for ($i = $controllerIndex; $i < count($url); $i++) {
            if (method_exists($controllerClassName, $url[$i])) {

                $actionIndex = $i;
                break;
            }
        }
        return $actionIndex;
    }

    private static function getControllerPrefix($url, $controllerIndex)
    {
        $prefix = '';
        //$paramsIndex = is_subclass_of($controller, 'Api') ? 1 : 2;
        for ($i = 0; $i < $controllerIndex; $i++) {
            $prefix .= $url[$i] . "/";
        }
        $prefix = trim($prefix, '/');
        return $prefix;
    }

    private static function getActionPrefix($url, $controllerIndex, $actionIndex)
    {
        $prefix = '';
        //$paramsIndex = is_subclass_of($controller, 'Api') ? 1 : 2;
        for ($i = $controllerIndex + 1; $i < $actionIndex; $i++) {
            $prefix .= $url[$i] . "/";
        }

        $prefix = trim($prefix, '/');
        return $prefix;
    }

    private static function getParams($segments, $startIndex)
    {
        $params = [];
        //$startIndex = is_subclass_of($controller, 'Api') ? 1 : 2;
        if (isset($segments[$startIndex])) {
            for ($i = $startIndex + 1; $i < sizeof($segments); $i++) {
                $param = $segments[$i];
                array_push($params, $param);
            }
        }
        return $params;
    }

    public static function getInputPrefix()
    {
        $appPrefix = null;

        // Buscar el índice de inicio de los parámetros
        $paramsStartIndex = strpos(DefaultUrl::$pattern, '{');

        // Extraer el prefix si está presente
        if ($paramsStartIndex > 0) {
            $appPrefix = substr(DefaultUrl::$pattern, 0, $paramsStartIndex);
            //$prefix = rtrim($prefix, '/');
            //$prefix = $prefix.'/';
        }

        $appPrefix = trim($appPrefix, '/');
        return $appPrefix;
    }
}
