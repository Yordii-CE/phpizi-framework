<?php

use Framework\Response\View;

function RENDER()
{
    //RENDER() SOLO DEBEJE EJECUTARSE EN TEMPLATE

    extract(View::$vars);

    //Obtener el valor del parametro $viewPath de renderView()
    $context = debug_backtrace(false)[2];
    $viewPath = $context["args"][0];
    include $viewPath;
}
