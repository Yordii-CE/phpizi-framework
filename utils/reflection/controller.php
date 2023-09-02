<?php

use Core\Annotations\Routing\Prefix;
use Core\Annotations\Request\Middlewares;
use Core\Annotations\Persistence\Model;
use Core\Annotations\Http\Get;
use Core\Annotations\Http\Post;
use Core\Annotations\Http\Put;
use Core\Annotations\Http\Delete;

class ControllerReflectionUtils extends ReflectionUtils
{
    function __construct(String $controllerClassName)
    {
        parent::__construct($controllerClassName, null);
    }
    function getControllerName()
    {
        $controllerName = str_replace("Controller", "", $this->reflection->getName());

        return $controllerName;
    }

    function getPrefix()
    {
        $prefix = "";
        $attributes = $this->reflection->getAttributes(Prefix::class);
        if (!empty($attributes)) {
            $prefixAttributeValue = $attributes[0]->newInstance()->prefix;

            $prefixName = rtrim($prefixAttributeValue, '/');
            $prefix = $prefixName;
        }

        return $prefix;
    }

    function getMiddlewares()
    {
        $middlewares = [];
        $attributes = $this->reflection->getAttributes(Middlewares::class);

        if (!empty($attributes)) {

            $middsattributes = $attributes[0]->newInstance()->middlewares;
            foreach ($middsattributes as $midd) {
                $middPath = Path::getPathMiddlewares($midd);
                if (!file_exists($middPath)) throw new Exception("'$midd' Middleware not found");

                array_push($middlewares, $midd);
            }
        }

        return $middlewares;
    }

    function isApi()
    {
        $controllerClassName = $this->reflection->getName();
        return is_subclass_of($controllerClassName, 'Api');
    }
    function getActionsHttpMethod($httpMethod): array
    {
        $httpMethods = [
            "GET" => Get::class,
            "POST" => Post::class,
            "PUT" => Put::class,
            "DELETE" => Delete::class,
        ];
        $methods = $this->reflection->getMethods();
        $actions = [];

        foreach ($methods as $reflectionMethod) {
            $attributes = $reflectionMethod->getAttributes($httpMethods[$httpMethod]);

            if (!empty($attributes)) {
                $actionName = $reflectionMethod->getName();
                $actionReflection = new ActionReflectionUtils($this->reflection->getName(), $actionName);
                $actionPrefix = $actionReflection->getPrefix();

                array_push($actions, [
                    "prefix" => strtolower($actionPrefix),
                    "name" => strtolower($actionName)
                ]);
            }
        }
        return $actions;
    }
    function getActionWithMatchingPrefix($prefix)
    {
        $methods = $this->reflection->getMethods();
        $action = null;

        foreach ($methods as $reflectionMethod) {
            $attributes = $reflectionMethod->getAttributes(Prefix::class);

            if (!empty($attributes)) {
                $controller = $this->getControllerName();
                $actionPrefix = $attributes[0]->newInstance()->prefix;
                $actioName = $reflectionMethod->getName();

                if (strpos($prefix, $actionPrefix) === 0) {

                    $action = [
                        "controller" => strtolower($controller),
                        "prefix" => strtolower($actionPrefix),
                        "name" => strtolower($actioName)
                    ];
                    break;
                }
            }
        }
        return $action;
    }
    function getModel()
    {
        // $inContext = Meta::validateFunctionContext("Controller", "__construct");
        // if ($inContext !== true)  throw new Exception($inContext);

        $modelName = $this->getControllerName(); //default model
        $attributes = $this->reflection->getAttributes(Model::class);

        if (!empty($attributes)) {
            $modelName = $attributes[0]->newInstance()->name;
            if (!file_exists(Path::getPathModel($modelName))) throw new Exception("'$modelName' model not found");
        }
        return $modelName;
    }
}
