<?php

namespace Framework\Utils\Url;

use Framework\Utils\Routing\Path;
use Framework\Utils\Routing\NamespaceManager;

class InputUrl
{

    //App prefix
    public static $inputPrefix = null;

    public static function getComponents($actionPlaceholder = null)
    {
        $url = $_GET['url'] ?? '';

        if ($actionPlaceholder !== null) {
            $url = Path::addCharacterToEndIfNeeded($url, '/');
            $url = InputUrl::setActionInURL($url, $actionPlaceholder);
        }

        $url = InputUrl::explodeUrl($url);

        $controllerPrefix = null;
        $controller = null;
        $actionPrefix = null;
        $action = null;
        $params = [];

        if (empty($url)) {
            return new UrlComponent(InputUrl::$inputPrefix, $controllerPrefix, $controller, $actionPrefix, $action, $params);
        }
        //AppPrefix
        if (InputUrl::existsAppPrefixInUrl($url)) {
            //Quitamos appPrefix del array $url.
            $urlWithoutAppPrefix = InputUrl::removeAppPrefixFromUrl($url);
            $url = InputUrl::explodeUrl($urlWithoutAppPrefix);
        }

        //Controller Prefix and Controller
        $controllerIndex = InputUrl::findControllerIndexInUrl($url);
        if ($controllerIndex === null) {
            //Entonces no es controller, todo el $url sera controllerPrefix
            $urlString = implode('/', $url);
            $urlString = trim($urlString, '/');
            $controllerPrefix =  $urlString;

            return new UrlComponent(InputUrl::$inputPrefix, $controllerPrefix, $controller, $actionPrefix, $action, $params);
        }

        $controllerPrefix = InputUrl::getControllerPrefix($url, $controllerIndex);
        $controller = $url[$controllerIndex];
        $actionIndex = InputUrl::findActionIndexInUrl($url, $controllerIndex);

        //Action Prefix and Action
        if ($actionIndex === null) {
            //Entonces no es action, todo el $url desde $controllerIndex + 1 hasta el final sera actionPrefix
            $actionPrefix = implode('/', array_slice($url, $controllerIndex + 1)) ?? null;
        } else {
            $action = $url[$actionIndex];
            $actionPrefix = InputUrl::getActionPrefix($url, $controllerIndex, $actionIndex);
        }

        //Params
        $lenActionPrefix = empty($actionPrefix) ? 0 : count(explode('/', $actionPrefix));
        $startIndex = $controllerIndex + $lenActionPrefix;

        $params = InputUrl::getParams($url, $startIndex + 1);

        return new UrlComponent(InputUrl::$inputPrefix, $controllerPrefix, $controller, $actionPrefix, $action, $params);
    }
    public static function setActionInURL($url, $actionPlaceholder)
    {
        $routePattern = $actionPlaceholder['controller'] . '/' . Path::appendIfNotEmpty($actionPlaceholder['prefix'], '/');
        $routePattern = preg_quote($routePattern, '/');

        $actionName = $actionPlaceholder['name'] . '/';

        $url = preg_replace("/($routePattern)/", "$0" . $actionName, $url, 1);
        return $url;
    }

    public static function existsAppPrefixInUrl($url)
    {
        $urlString = implode("/", $url) . '/';
        return strpos($urlString, DefaultUrl::$defaultPrefix . '/') === 0;
    }
    public static function removeAppPrefixFromUrl($url)
    {
        $urlString = implode("/", $url) . '/';
        InputUrl::$inputPrefix = DefaultUrl::$defaultPrefix;
        $urlWithoutAppPrefix = str_replace(DefaultUrl::$defaultPrefix . "/", "", $urlString);
        return $urlWithoutAppPrefix;
    }

    private static function explodeUrl($url): array
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
            if (class_exists(NamespaceManager::$controllers . $url[$i])) {
                $controllerIndex = $i;
                break;
            }
        }
        return $controllerIndex;
    }

    public static function findActionIndexInUrl($url, $controllerIndex): ?int
    {

        $controllerClassName = $url[$controllerIndex];
        $actionIndex = null;
        for ($i = $controllerIndex; $i < count($url); $i++) {
            if (method_exists(NamespaceManager::$controllers . $controllerClassName, $url[$i])) {

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
