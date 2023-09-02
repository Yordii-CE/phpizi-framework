<?php

class Framework
{
    public static function requireFolder($folder)
    {
        $folder = trim($folder, '/');
        $cores = glob(__DIR__ . "\\.." . "\\$folder\*");
        foreach ($cores as $file) {
            if (is_file($file)) include $file;
        }
    }
}
