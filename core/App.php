<?php

namespace Framework\Core;

use Framework\Core\Program;
use Framework\Utils\Url\DefaultUrl;
use Framework\Utils\Hash;


class App
{
    private static $starting = false;
    public static function controllers(array $controllers)
    {
        if (App::$starting) die("The app should not start yet");
        Hash::createControllerHash($controllers);
    }
    public static function startApp()
    {
        App::$starting = true;
        //GLOBALS FUNCTIONS FOR APP
        require_once __DIR__ . '/../global_funcs/json.php';
        require_once __DIR__ . '/../global_funcs/redirectToAction.php';
        require_once __DIR__ . '/../global_funcs/redirectToUrl.php';
        require_once __DIR__ . '/../global_funcs/render.php';
        require_once __DIR__ . '/../global_funcs/to.php';
        require_once __DIR__ . '/../global_funcs/view.php';

        require_once 'app/main.php';
        Program::start();
    }
    public static function matchUrl($pattern)
    {
        if (App::$starting) die("The app should not start yet");
        DefaultUrl::$pattern = $pattern;
    }
}
