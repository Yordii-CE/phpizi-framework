<?php
class App
{
    public static function requireControllers()
    {
        $controllers = glob(Path::$folderControllers . '/*.php');

        foreach ($controllers as $file) {
            include $file;
        }
    }
    public static function requireFolder($folder)
    {
        $folder = trim($folder, '/');
        $cores = glob("app/$folder/*");

        foreach ($cores as $file) {
            if (is_file($file)) include $file;
        }
    }
}
