<?php

// Load environment variables from .env file
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Optional Composer autoload for external libraries (PHPMailer, etc.)
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

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
