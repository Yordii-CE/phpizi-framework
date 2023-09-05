<?php

namespace Framework\Utils\Url;

class UsingUrl
{
    public static function parseComponents($inputComponents, $defaultComponents): UrlComponent
    {
        $appPrefix = $inputComponents->appPrefix;
        $controllePrefix = $inputComponents->controllerPrefix;
        $controller = isset($_GET['url']) ? $inputComponents->controller ?? $defaultComponents->controller : $defaultComponents->controller;
        $actionPrefix = isset($_GET['url']) ? $inputComponents->actionPrefix ?? $defaultComponents->actionPrefix : $defaultComponents->actionPrefix;
        $action = isset($_GET['url']) ? $inputComponents->action ?? $defaultComponents->action : $defaultComponents->action;
        $params = (!isset($_GET['url']) || empty($inputComponents->params)) && $inputComponents->controller == $defaultComponents->controller ? $defaultComponents->params : $inputComponents->params;

        return new UrlComponent(
            $appPrefix,
            $controllePrefix,
            $controller,
            $actionPrefix,
            $action,
            $params
        );
    }
}
