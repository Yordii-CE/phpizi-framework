<?php

use Framework\Utils\Routing\Router;

function TO($controllerName, $actionName = '', $params = [])
{
    //TO() SOLO DEBEJE EJECUTARSE EN VISTAS

    $paramsString = '';

    if (!empty($params)) {
        $paramsString = implode('/', $params);
    }

    $route = Router::getRouteTo($controllerName, $actionName, $paramsString);
    return $route;
}
