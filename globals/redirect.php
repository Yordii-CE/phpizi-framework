<?php
function redirectToAction(): Redirect
{
    $inContext = Meta::validateFunctionContext("Controller", "action");

    if ($inContext !== true)  throw new Exception($inContext);

    $args = func_get_args();

    $controller = strtolower(str_replace("Controller", "", Meta::getCurrentController()));
    $action = isset($args[0]) ? $args[0] : '';

    //params
    $paramsString = '';

    if (count($args) > 1) {
        if (is_string($args[1])) {
            $controller = $args[1];
        } elseif (is_array($args[1])) {
            $paramsString = implode('/', $args[1]);
        }
    }

    if (count($args) > 2) {
        $paramsString = implode('/', $args[2]);
    }

    return new RedirectToAction($controller, $action, $paramsString);
}


function redirectToUrl($url): Redirect
{
    $inContext = Meta::validateFunctionContext("Controller", "action");

    if ($inContext !== true)  throw new Exception($inContext);

    return new RedirectToUrl($url);
}
