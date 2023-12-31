<?php

namespace Framework\Core;

use Framework\Utils\Url\DefaultUrl;
use Framework\Utils\Url\InputUrl;
use Framework\Utils\Url\UsingUrl;
use Framework\Utils\Routing\Path;
use Framework\Definitions\Exceptions\ControllerException;
use Framework\Definitions\Exceptions\ApiException;
use Framework\Response\Json;
use Framework\Response\View;
use Framework\Request\Body;
use Framework\Definitions\Abstracts\Redirect;
use Framework\Utils\Reflection\ControllerReflection;
use Framework\Utils\Reflection\ActionReflection;
use Framework\Utils\Reflection\ModelReflection;
use Framework\Utils\Namespaces\ControllerNamespace;
use Framework\Utils\Namespaces\MiddlewareNamespace;

class Program
{
    private static $controllerReflection;
    private static $actionReflection;
    private static $modelReflection;

    public static function getControllerInstance($controllerClass)
    {
        //Load models
        $models = Program::$controllerReflection->getConstructorModels(); //If is empty, model has same name controller
        $modelInstances = [];
        foreach ($models as $model) {
            $modelClass = $model['type'];

            //Load Database
            Program::$modelReflection = new ModelReflection($modelClass);
            $databases = Program::$modelReflection->getConstructorDatabases();
            $databaseInstances = [];
            foreach ($databases as $database) {
                $databaseClass = $database['type'];
                $databaseInstance =  new $databaseClass;
                array_push($databaseInstances, $databaseInstance);
            }

            $modelInstance = new $modelClass(...$databaseInstances);
            array_push($modelInstances, $modelInstance);
        }

        return new $controllerClass(...$modelInstances);
    }

    public static function start()
    {
        try {
            //url_debug();

            if (!DefaultUrl::validatePatternFormat()) throw new \Exception("Wrong default url format by default");

            $defaultUrl = DefaultUrl::getComponents();
            $inputUrl = InputUrl::getComponents();
            $usingUrl = UsingUrl::parseComponents($inputUrl, $defaultUrl);

            $controllerNamespace = ControllerNamespace::getNamespaceOf($usingUrl->controller);
            $controllerClass = $controllerNamespace . $usingUrl->controller;
            $actionName = $usingUrl->action;

            //Controller 
            if (!file_exists(str_replace('\\', '/', $controllerClass . '.php'))) throw new ControllerException("'$controllerClass' controller not found");
            Program::$controllerReflection = new ControllerReflection($controllerClass);

            $controller = Program::getControllerInstance($controllerClass);

            //Check action

            if (Program::$controllerReflection->isApi()) {
                //Es un Api 

                if (empty($inputUrl->action)) {
                    //No hay Action                    
                    if (!empty($inputUrl->actionPrefix)) {
                        //Si hay ActionPrefix
                        // Obtengo action cuyo prefix coincide con el prefix mal formado de inputUrl
                        $matchingAction = Program::$controllerReflection->getActionWithMatchingPrefix($inputUrl->actionPrefix);
                        if ($matchingAction !== null) {

                            //actionPrefix SON ACTIONPREFIX                            
                            $actionPlaceholderValue = [
                                'controller' => $matchingAction['controller'],
                                'prefix' => $matchingAction['prefix'],
                                'name' => $matchingAction['name']
                            ];
                            $inputUrl = InputUrl::getComponents($actionPlaceholder = $actionPlaceholderValue);
                        } else {
                            //actionPrefix SON PARAMETROS
                            $inputUrl->params = explode('/', $inputUrl->actionPrefix);
                            $inputUrl->actionPrefix = null;
                        }
                    }
                }

                $usingUrl = UsingUrl::parseComponents($inputUrl, $defaultUrl);

                $httpMethod = $_SERVER['REQUEST_METHOD'];
                $actions = Program::$controllerReflection->getActionsHttpMethod($httpMethod);

                $actionName = '';
                foreach ($actions as $action) {
                    $prefix = $action['prefix'];
                    $name = $action['name'];
                    //Opcionl el nombre del metodo para comunicacion entre controladores y api
                    $optionalMethodName = (empty($inputUrl->action) or $inputUrl->action === $name);
                    if ($inputUrl->actionPrefix == $prefix and $optionalMethodName) {
                        $actionName = $name;
                        break;
                    }
                }

                //Quisas no hay action para httpMethod
                if (!method_exists($controller, $actionName)) {
                    if (empty($actions)) throw new ApiException("'$httpMethod' method not implemented");
                    else throw new ApiException("Api route not found");
                }
            } else {
                //Es un Controller
                if (!method_exists($controller, $actionName))  throw new ControllerException("'$actionName' action not found");
            }

            Program::$actionReflection = new ActionReflection($controllerClass, $actionName);

            // Check prefix  
            Program::checkPrefix($defaultUrl, $usingUrl);

            //Get action body param
            $params = $usingUrl->params;
            $body = Program::getActionBodyParam();
            array_push($params, $body);

            //Middlewares   
            Program::loadMiddlewares($body);

            //call action   
            $response = Program::callAction($controller, $actionName, $params);

            if ($response instanceof View) $response?->render();
            if ($response instanceof Json) $response?->returnJson();
            if ($response instanceof Redirect) $response?->redirect();
        } catch (ApiException $e) {
            Program::handleError($e->getMessage(), $apiError = true);
        } catch (ControllerException $e) {
            Program::handleError($e->getMessage(), $apiError = false);
        } catch (\Exception $e) {
            Program::handleError($e->getMessage());
        }
    }


    private static function checkPrefix($defaultUrl, $usingUrl)
    {

        $controllerReflection = Program::$controllerReflection;

        $route = Path::appendIfNotEmpty($defaultUrl->appPrefix, '/') . $controllerReflection->getPrefix();
        $usingRoute = Path::appendIfNotEmpty($usingUrl->appPrefix, '/') . $usingUrl->controllerPrefix;

        if (!empty($usingUrl->action)) {
            $actionReflection = Program::$actionReflection;

            $route = Path::appendIfNotEmpty($route, '/') . $actionReflection->getPrefix();
            $usingRoute = Path::appendIfNotEmpty($usingRoute, '/') . $usingUrl->actionPrefix;
        }

        if ($route != $usingRoute) {
            if (Program::$controllerReflection->isApi()) throw new ApiException("Api route not found");
            else  throw new \Exception("Route not found");
        }
    }
    private static function loadMiddlewares($middlewareParam) // $middlewareParam is Body
    {
        //Unimos los middlewares del controller action
        $middlewares = array_merge(Program::$controllerReflection->getMiddlewares(), Program::$actionReflection->getMiddlewares());

        foreach ($middlewares as $midd) {
            $middlewareClass =  $midd; //Ya vienen con namespace

            $middleware = new $middlewareClass();
            $return = $middleware->handle($middlewareParam);
            if ($return instanceof Redirect) $return?->redirect();
        }
    }
    private static function getActionBodyParam()
    {
        $jsonData = file_get_contents("php://input");
        $json = json_decode($jsonData, true);

        $body = new Body(empty($_POST) ? empty($json) ? $_GET : $json : $_POST);
        return $body;
    }

    private static function handleError($message, $apiError = false)
    {
        $controllerNamespace = ControllerNamespace::getNamespaceOf("Error");
        $errorControllerClass = $controllerNamespace . "Error";
        $errorController = new $errorControllerClass();
        if (!$apiError) {
            $response = $errorController->index($message);
            $response?->render();
        } else {
            $response = $errorController->apiNotFound($message);
            $response?->returnJson();
        }
    }

    private static function callAction($controller, $methodName, $urlParams)
    {
        //Parametros que Action espera
        $allParameters = Program::$actionReflection->getParameters('all');
        //Parametros que Action espera sin valor
        $valuelessParameters = Program::$actionReflection->getParameters('valueless');

        $bodyParam = Program::$actionReflection->findParameterByType('Body');
        if ($bodyParam === null) {
            //No espera recibir a Body, lo quitamos
            array_pop($urlParams);
        }

        //PRIMERA VERIFICACION:
        if (count($urlParams) > count($allParameters)) {
            $methodString = Program::$actionReflection->toString($allParameters, $urlParams);
            throw new \Exception("Too many parameters for $methodString");
        }

        if (count($urlParams) < count($valuelessParameters)) {
            $methodString = Program::$actionReflection->toString($allParameters, $urlParams);
            throw new \Exception("Few parameters for $methodString");
        }

        if ($bodyParam !== null) {
            //Si espera recibir a Body, establecemos valor Body a su correspondiente parametro
            $bodyIndex = $bodyParam['index'];
            $allParameters[$bodyIndex]['value'] = end($urlParams);
            //Quitamos valor Body del url array
            array_pop($urlParams);
        }

        //Establecemos a los parametros vacios sus valores
        for ($i = 0; $i < count($allParameters); $i++) {

            if (isset($urlParams[0])) {
                if ($allParameters[$i]['type'] !== 'Body') {
                    $allParameters[$i]['value'] = $urlParams[0];
                    unset($urlParams[0]);
                    $urlParams = array_values($urlParams);
                }
            }
        }
        //SEGUNDA VERIFICACION: Validar que todos tenga valor y no null
        $paramsNullValue = array_filter($allParameters, fn ($p) => !isset($p['value']));

        if (!empty($paramsNullValue)) {
            $methodString = Program::$actionReflection->toString($allParameters, $urlParams);
            throw new \Exception("Few parameters for $methodString");
        }

        $paramsValue = array_map(fn ($p) => $p['value'], $allParameters);
        // echo "<pre>";
        // print_r($urlParams);
        // echo "<br>";
        // print_r($allParameters);
        // echo "<br>";
        // print_r($paramsValue);

        return call_user_func_array(array($controller, $methodName), $paramsValue);
    }
}
