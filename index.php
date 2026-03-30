<?php

spl_autoload_register(function ($class) {
    // Transforme 'Controllers\ProductController' en 'controllers/ProductController.php'
    $parts = explode('\\', $class);
    $parts[0] = strtolower($parts[0]);
    $classPath = implode('/', $parts);
    
    $file = __DIR__ . '/' . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

use Services\Router;
use Services\Session;

Session::start();

$router = new Router();
$router->handleRequest();
