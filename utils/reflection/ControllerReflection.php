<?php

namespace Framework\Utils\Reflection;

use Framework\Definitions\Abstracts\ReflectionUtils;
use Framework\Utils\Reflection\ActionReflection;
use Framework\Utils\Routing\Path;
use Framework\Definitions\Annotations\Routing\Prefix;
use Framework\Definitions\Annotations\Request\Middlewares;
use Framework\Definitions\Annotations\HttpMethods\Get;
use Framework\Definitions\Annotations\HttpMethods\Post;
use Framework\Definitions\Annotations\HttpMethods\Put;
use Framework\Definitions\Annotations\HttpMethods\Delete;
use Framework\Utils\Namespaces\HelpersNamespaces;
use Framework\Utils\Namespaces\FrameworkNamespaces;



class ControllerReflection extends ReflectionUtils
{
    function __construct(String $controllerClass)
    {
        parent::__construct($controllerClass, null);
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
                $middPath = HelpersNamespaces::convertToPath($midd);
                if (!file_exists($middPath)) throw new \Exception("'$midd' Middleware not found");

                array_push($middlewares, $midd);
            }
        }

        return $middlewares;
    }

    function isApi()
    {
        $controllerClassName = $this->reflection->getName();
        return is_subclass_of($controllerClassName, FrameworkNamespaces::$abstracts . 'Api');
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
                $actionReflection = new ActionReflection($this->reflection->getName(), $actionName);
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
                $controller = $this->reflection->getName();
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
    function getConstructorModels()
    {
        $constructor = $this->reflection->getConstructor();
        $models = [];

        if ($constructor) {
            $params = $constructor->getParameters();

            foreach ($params as $param) {
                array_push($models, [
                    "name" => $param->getName(),
                    "type" => ($param->hasType() ? $param->getType()->getName() : "")

                ]);
            }
        }
        return $models;
    }
}
