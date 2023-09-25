<?php

use Framework\Utils\Routing\Path;

function INCLUDE_FILE($fileName, $vars = [])
{
    //INCLUDE_FILE() SOLO DEBEJE EJECUTARSE EN VISTAS / TEMPLATE
    if (!file_exists(Path::getPathView($fileName))) throw new Exception("ERROR REQUIRE FILE: not found");

    extract($vars);
    include Path::getPathView($fileName);
}
