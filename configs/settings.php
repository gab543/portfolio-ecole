<?php

return [
    'database' => (function () {
        if ($url = getenv('POSTGRES_URL')) {
            $parsed = parse_url($url);
            return [
                'driver' => 'pgsql',
                'host' => $parsed['host'],
                'dbname' => ltrim($parsed['path'], '/'),
                'user' => $parsed['user'],
                'password' => $parsed['pass'],
                'port' => $parsed['port'] ?? '6543'
            ];
        }

        //Pour MAMP
        return [
            'driver' => 'mysql',
            'host' => 'localhost',
            'dbname' => 'portfolio',
            'user' => 'root',
            'password' => 'root',
            'port' => '8889'
        ];
    })(),
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
        '/admin/projects' => ['controller' => 'Controllers\\AdminController', 'action' => 'projects'],
        '/admin/projects/create' => ['controller' => 'Controllers\\AdminController', 'action' => 'createProject'],
        '/admin/projects/store' => ['controller' => 'Controllers\\AdminController', 'action' => 'storeProject'],
        '/admin/projects/edit' => ['controller' => 'Controllers\\AdminController', 'action' => 'editProject'],
        '/admin/projects/update' => ['controller' => 'Controllers\\AdminController', 'action' => 'updateProject'],
        '/admin/projects/delete' => ['controller' => 'Controllers\\AdminController', 'action' => 'deleteProject'],
        '/admin/request-access' => ['controller' => 'Controllers\\AuthController', 'action' => 'requestAccess'],
        '/admin/submit-request' => ['controller' => 'Controllers\\AuthController', 'action' => 'submitRequest'],
        '/admin/confirm-access' => ['controller' => 'Controllers\\AuthController', 'action' => 'confirmAccess']
    ]
];
