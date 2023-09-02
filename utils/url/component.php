<?php
class UrlComponent
{
    function __construct(public $appPrefix, public $controllerPrefix, public $controller, public $actionPrefix, public $action, public $params)
    {
        //Clean prefix
        $this->appPrefix = strtolower(trim($appPrefix, '/')); //Aunque ya lo hacemos en Default:: y Input::
        $this->controllerPrefix = strtolower(trim($controllerPrefix, '/'));
        $this->actionPrefix = strtolower(trim($actionPrefix, '/'));

        //strtolower() si es null lo convierte a string vacio
        $this->action = $action;
        $this->controller = $controller;
    }
}
