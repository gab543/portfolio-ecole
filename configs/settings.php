<?php

return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'portfolio',
        'user' => 'root',
        'password' => '', // Or 'root' based on environment
        'port' => '8889'
    ],
    'routes' => [
        '/' => ['controller' => 'Controllers\\FrontController', 'action' => 'home'],
        '/project' => ['controller' => 'Controllers\\FrontController', 'action' => 'projectDetail'],
        '/admin/login' => ['controller' => 'Controllers\\AuthController', 'action' => 'login'],
        '/admin/authenticate' => ['controller' => 'Controllers\\AuthController', 'action' => 'authenticate'],
        '/admin/logout' => ['controller' => 'Controllers\\AuthController', 'action' => 'logout'],
        '/admin' => ['controller' => 'Controllers\\AdminController', 'action' => 'dashboard'],
        '/admin/profile' => ['controller' => 'Controllers\\AdminController', 'action' => 'profile'],
        '/admin/profile/update' => ['controller' => 'Controllers\\AdminController', 'action' => 'updateProfile']
    ]
];
