<?php

namespace Framework\Core;

use Framework\Core\Program;
use Framework\Utils\Url\DefaultUrl;
use Framework\Utils\Hash;


class App
{
    private static $controllersStarted = false;
    private static $matchUrlStarted = false;
    public static function controllers(array $controllers)
    {
        Hash::createControllerHash($controllers);
        App::$controllersStarted = true;
    }
    public static function startApp()
    {
        if (!App::$matchUrlStarted) die("Initialize match url");
        if (!App::$controllersStarted) die("Initialize controllers");

        //GLOBALS FUNCTIONS FOR APP
        require_once __DIR__ . '/../global_funcs/json.php';
        require_once __DIR__ . '/../global_funcs/redirectToAction.php';
        require_once __DIR__ . '/../global_funcs/redirectToUrl.php';
        require_once __DIR__ . '/../global_funcs/render.php';
        require_once __DIR__ . '/../global_funcs/to.php';
        require_once __DIR__ . '/../global_funcs/view.php';
        require_once __DIR__ . '/../global_funcs/include_file.php';

        require_once 'app/main.php';
        Program::start();
    }
    public static function matchUrl($pattern)
    {
        DefaultUrl::$pattern = $pattern;
        App::$matchUrlStarted = true;
    }
}
