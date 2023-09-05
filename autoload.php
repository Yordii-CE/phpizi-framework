<?php
// Definición de una función de autoloading personalizada
spl_autoload_register(function ($className) {
    // Convierte el espacio de nombres en una ruta de archivo
    $classFile = str_replace('\\', '/',  $className) . '.php';
    // Comprueba si el archivo existe y lo incluye si es así
    //echo $classFile . "<br>";
    // require_once  $classFile;
    if (file_exists($classFile)) {
        require_once $classFile;
    } else {
        //echo "File not found: $classFile";
    }
});

// Ahora, cuando intentes instanciar una clase, PHP buscará automáticamente y cargará el archivo correspondiente si aún no se ha incluido.
