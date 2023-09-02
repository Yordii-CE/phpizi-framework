<?php

use Core\Annotations\Routing\Prefix;
use Core\Annotations\Request\Middlewares;

class ActionReflectionUtils extends ReflectionUtils
{
    function __construct(String $controllerClassName, string $actionName)
    {
        $actionName = empty($actionName) ? 'index' : $actionName;
        parent::__construct($controllerClassName, $actionName);
    }

    function getMethodName()
    {
        $controllerName = str_replace("Controller", "", $this->reflection->getName());

        return $controllerName;
    }

    function getParameters($filter = 'all')
    {
        $parameters = $this->reflection->getParameters();
        $paramInfo = [];

        foreach ($parameters as $parameter) {
            $paramName = $parameter->getName();

            $hasDefaultValue = $parameter->isDefaultValueAvailable();
            $defaultValue = null;

            if ($hasDefaultValue) {
                $defaultValue = $parameter->getDefaultValue();
            }

            $paramType = $parameter->getType();
            $paramTypeString = null;

            if ($paramType) {
                $paramTypeString = $paramType->getName();
            }

            if ($filter == 'all') {
                $paramInfo[] = [
                    'name' => $paramName,
                    'value' => $defaultValue,
                    'type' => $paramTypeString
                ];
            }
            if ($filter == 'valueless') {
                if ($defaultValue === null) {
                    //Si el tipo es Body no lo contamos como si no tuviera valor ya que el no lo recibe por url
                    if ($paramTypeString != 'Body') {
                        $paramInfo[] = [
                            'name' => $paramName,
                            'value' => $defaultValue,
                            'type' => $paramTypeString
                        ];
                    }
                }
            }
        }

        return $paramInfo;
    }

    public function findParameterByType($type)
    {
        $allParameters = $this->getParameters('all');
        $param = null;
        //Find param    
        for ($i = 0; $i < count($allParameters); $i++) {
            $p = $allParameters[$i];
            if ($p['type'] == $type) {
                $p["index"] = $i;
                $param = $p;
                break;
            }
        }

        return $param;
    }

    public function toString($allParameters, $urlParams)
    {
        $methodName = $this->getMethodName();
        //$parameterValues = array_slice($urlParams, 0, count($allParameters));

        $bodyParam = $this->findParameterByType('Body');
        if ($bodyParam != null) {
            //set Body param
            for ($i = 0; $i < count($allParameters); $i++) {
                if ($allParameters[$i]['type'] == 'Body') {
                    $allParameters[$i]['value'] = 'Body';
                }
            }
        }
        //  Quitar Body param
        unset($urlParams[count($urlParams) - 1]);

        for ($i = 0; $i < count($allParameters); $i++) {
            if (isset($urlParams[$i])) $allParameters[$i]['value'] = $urlParams[$i];
        }


        // echo "<pre>";
        // print_R($allParameters);

        //Only name param with variable format -> action($id=2, ....)
        $parametersName = [];
        for ($i = 0; $i < count($allParameters); $i++) {
            $parameter = $allParameters[$i];
            if ($parameter['value'] === null) {
                if ($parameter['type'] == "Body") {
                    //-> $var = BodyObj
                    array_push($parametersName, "$" . $parameter['name'] . " == Body");
                } else {
                    //-> $var
                    array_push($parametersName, "$" . $parameter['name']);
                }
            } else {
                //-> $var = 'value'
                array_push($parametersName, "$" . $parameter['name'] . "=" . $parameter['value']);
            }
        }

        //$parametersName = array_map(fn ($e) => $e['defaultValue'] === null ? "$" . $e['name'] : "$" . $e['name'] . "=" . $e['defaultValue'], $parameters);


        $parametersString = trim(implode(', ', $parametersName), ',');
        return "<span style='color:#d0b862; font-weight:bold;'>
                    $methodName
                    <span style='color:#b6391f;'>(</span>
                </span>
                $parametersString 
                <span style='color:#b6391f;font-weight:bold;'>)</span>";
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
}
