<?php

use Framework\Response\View;
use Framework\Utils\Routing\Path;

function INCLUDE_FILE($filePath, $vars = [])
{
    //INCLUDE_FILE() SOLO DEBEJE EJECUTARSE EN VISTAS / TEMPLATE

    extract(View::$vars);

    $route = Path::getPathView($THIS) . '/' . $filePath;

    if (!file_exists($route)) throw new Exception("ERROR REQUIRE FILE: not found");

    extract($vars);
    include $route;
}
