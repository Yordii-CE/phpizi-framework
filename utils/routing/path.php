<?php

//Only for App, not for Core
class Path
{
    public static $folderControllers = 'app/controllers/';
    public static $folderDatabases = 'app/databases/';
    public static $folderLibs = 'app/libs/';
    public static $folderMiddlewares = 'app/middlewares/';
    public static $folderModels = 'app/models/';
    public static $folderViews = 'app/views/';
    public static $folderPublic = 'app/public';

    static function getPathDatabaseConfig($databaseName)
    {
        return Path::$folderDatabases . $databaseName . '.php';
    }

    static function getPathMiddlewares($middleware)
    {

        return Path::$folderMiddlewares . $middleware . '.php';
    }

    static function getPathController($controllerName)
    {
        // $controllerFileName = $controllerName . '.controller.php';
        // $path = Path::getFilePathRecursively(Path::$folderControllers, $controllerFileName);
        // return $path;
        return Path::$folderControllers . $controllerName . '.controller.php';
    }

    static function getPathModel($modelName)
    {
        return Path::$folderModels . $modelName . '.model.php';
    }

    static function getPathView($viewName)
    {
        return Path::$folderViews . $viewName;
    }

    static function getProjectPath()
    {
        $BASE_URL = 'http://' . $_SERVER['HTTP_HOST'];
        $projectPath = dirname($_SERVER['SCRIPT_NAME']);
        $projectName = $projectPath;

        $BASE_URL .= '/' . $projectName;

        return $BASE_URL;
    }

    static function appendIfNotEmpty($path, $char)
    {
        if (!empty($path)) return $path . $char;
        return $path;
    }
}
