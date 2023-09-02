<?php

class RedirectToUrl extends Redirect
{
    public function __construct($url)
    {
        parent::__construct($url);
    }
}


class RedirectToAction extends Redirect
{
    public $controllerName;
    public $actionName;
    public $paramsString;

    public function __construct($controllerName, $actionName, $paramsString)
    {
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->paramsString = $paramsString;

        $route = Router::getRouteTo($controllerName, $actionName, $paramsString);       
        parent::__construct($route);
    }
}
