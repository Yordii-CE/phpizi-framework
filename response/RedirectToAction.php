<?php

namespace Framework\Response;

use Framework\Definitions\Abstracts\Redirect;
use Framework\Utils\Routing\Router;

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
