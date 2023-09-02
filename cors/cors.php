<?php
class Cors
{
    static function enableCors($allowedControllers = [])
    {

        /*$defaultUrl = DefaultUrl::getUrl();
        $inputUrl = InputUrl::getUrl();
        $url = InputUrl::parseUrl($inputUrl, $defaultUrl);
        $controller = $url['controller'];

        if (isset($allowedControllers[$controller])) {

            $origin = "*";  // Puedes personalizar el origen si lo deseas
            header("Access-Control-Allow-Origin: $origin");
            $allowedMethods = $allowedControllers[$controller];

            header("Access-Control-Allow-Methods: " . implode(', ', $allowedMethods));
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            header("Access-Control-Expose-Headers: Content-Length, X-JSON");
            header("Access-Control-Max-Age: 86400");
            header("Access-Control-Allow-Credentials: true");
        } else {
            self::handleCorsError();
        }*/
    }

    static function handleCorsError()
    {
        header("HTTP/1.1 403 Forbidden");
        echo "Error: CORS issue detected. Access denied.";
        exit();
    }
}
