<?php
//TO() SOLO DEBEJE EJECUTARSE EN VISTAS
function TO($controllerName, $actionName = '', $params = [])
{
    //params
    $paramsString = '';

    if (!empty($params)) {
        $paramsString = implode('/', $params);        
    }

    $route = Router::getRouteTo($controllerName, $actionName, $paramsString);    
    return $route;
}
