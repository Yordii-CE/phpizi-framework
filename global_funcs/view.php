<?php

use Framework\Response\View;
use Framework\Utils\Reflection\Meta;
use Framework\Utils\Routing\NamespaceManager;

function view(): View
{
    $inContext = Meta::validateFunctionContext(["Controller"], "action");
    if ($inContext !== true)  throw new \Exception($inContext);

    $args = func_get_args();

    $currentController = NamespaceManager::removeControllerNamespace(Meta::getCurrentController());
    $viewName = $currentController . "/" . Meta::getCurrentAction();
    $model = null;
    $useTemplate = true;


    if (count($args) >= 1) {
        if (gettype($args[0]) == 'string') {

            $hasSlash = strpos($args[0], "/");

            if ($hasSlash) {
                $splitArgViewName = explode('/', $args[0]);
                $viewName = $splitArgViewName[0] . '/' . $splitArgViewName[1];
            } else {
                $viewName = $currentController . '/' . $args[0];
            }
        } elseif (gettype($args[0]) == 'array') {
            $model = $args[0];
        } elseif (gettype($args[0]) == 'boolean') {
            $useTemplate = $args[0];
        }
    }

    if (count($args) >= 2) {
        if (gettype($args[1]) == 'array') {
            $model = $args[1];
        } elseif (gettype($args[1]) == 'boolean') {
            $useTemplate = $args[1];
        }
    }

    if (count($args) >= 3) {
        $model = $args[1];
        $useTemplate = $args[2];
    }

    return new View($viewName, $model, $useTemplate);
}
