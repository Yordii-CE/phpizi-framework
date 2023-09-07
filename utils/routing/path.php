<?php

namespace Framework\Utils\Routing;
//Only for App, not for Core
class Path
{
    public static $folderViews = 'app/views/';
    public static $folderPublic = 'app/public';

    static function getPathView($viewName)
    {
        return Path::$folderViews . $viewName;
    }

    static function getProjectPath()
    {
        $BASE_URL = 'http://' . $_SERVER['HTTP_HOST'];
        $projectPath = dirname($_SERVER['SCRIPT_NAME']);

        return $BASE_URL . $projectPath;
    }

    static function appendIfNotEmpty($path, $char)
    {
        if (!empty($path)) return $path . $char;
        return $path;
    }
    static function addCharacterToEndIfNeeded($path, $char)
    {
        if (substr($path, -1) !== $char) {
            $path .= $char;
        }
        return $path;
    }
}
