<?php

use Core\Annotations\Persistence\Database;

class ModelReflectionUtils extends ReflectionUtils
{
    function __construct(String $controllerClassName)
    {
        parent::__construct($controllerClassName, null);
    }

    function getDatabase()
    {

        $databaseName = null;
        $attributes = $this->reflection->getAttributes(Database::class);
        if (!empty($attributes)) {
            $databaseName = $attributes[0]->newInstance()->name;
        }
        if ($databaseName == null) return null;
        $databasePath = Path::getPathDatabaseConfig($databaseName);
        if (!file_exists($databasePath)) throw new Exception("'$databaseName' Database not found");
        require_once $databasePath;
        $db = new $databaseName();

        return $db->connect();
    }
}
