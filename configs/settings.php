<?php

return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'portfolio',
        'user' => 'root',
        'password' => 'root',
        'port' => '8889'
    ],
    'mail' => [
        'provider' => getenv('MAIL_PROVIDER') ?: 'mailhog', // 'mailhog', 'mailjet', or 'mailjet_smtp'
        'host' => 'localhost',
        'port' => 1025,
        'smtp_auth' => false,
        'smtp_secure' => '',
        'username' => '',
        'password' => '',
        'from' => 'gabriel.caboche@gmail.com', // REMPLACEZ par votre email vérifié dans Mailjet
        'from_name' => 'Portfolio',
        'admin' => 'gabcab1002@gmail.com', // REMPLACEZ par votre email admin
        'mailjet' => [
            'api_key' => getenv('API_MAIL') ?: '',
            'secret_key' => getenv('SECRET_API_MAIL') ?: '',
        ],
        // Mailjet SMTP fallback (if API has SSL issues)
        'mailjet_smtp' => [
            'host' => 'in-v3.mailjet.com',
            'port' => 587,
            'smtp_auth' => true,
            'smtp_secure' => 'tls',
            'username' => getenv('API_MAIL') ?: '',
            'password' => getenv('SECRET_API_MAIL') ?: '',
        ],
    ],
    'routes' => [
        '/' => ['controller' => 'Controllers\\FrontController', 'action' => 'home'],
        '/project' => ['controller' => 'Controllers\\FrontController', 'action' => 'projectDetail'],
        '/admin/login' => ['controller' => 'Controllers\\AuthController', 'action' => 'login'],
        '/admin/authenticate' => ['controller' => 'Controllers\\AuthController', 'action' => 'authenticate'],
        '/admin/logout' => ['controller' => 'Controllers\\AuthController', 'action' => 'logout'],
        '/admin' => ['controller' => 'Controllers\\AdminController', 'action' => 'dashboard'],
        '/admin/profile' => ['controller' => 'Controllers\\AdminController', 'action' => 'profile'],
        '/admin/profile/update' => ['controller' => 'Controllers\\AdminController', 'action' => 'updateProfile'],
        '/admin/request-access' => ['controller' => 'Controllers\\AuthController', 'action' => 'requestAccess'],
        '/admin/submit-request' => ['controller' => 'Controllers\\AuthController', 'action' => 'submitRequest'],
        '/admin/confirm-access' => ['controller' => 'Controllers\\AuthController', 'action' => 'confirmAccess']
    ]
];
