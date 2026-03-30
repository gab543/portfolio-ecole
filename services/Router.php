<?php 
namespace Services;

class Router {
    private array $routes;

    public function __construct() {
        $settings = require __DIR__ . '/../configs/settings.php';
        $this->routes = $settings['routes'];
    }

    public function handleRequest() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && $scriptName !== '\\') {
            $uri = str_replace($scriptName, '', $uri);
        }
        if ($uri === '') {
            $uri = '/';
        }

        if (array_key_exists($uri, $this->routes)) {
            $controllerName = $this->routes[$uri]['controller'];
            $actionName = $this->routes[$uri]['action'];

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $actionName)) {
                    $controller->$actionName();
                } else {
                    $this->notFound("Methode introuvable");
                }
            } else {
                $this->notFound("Controleur introuvable: " . $controllerName);
            }
        } else {
            $this->notFound("Route introuvable: " . htmlspecialchars($uri));
        }
    }

    private function notFound($message) {
        http_response_code(404);
        require __DIR__ . '/../views/404.phtml';
    }
}
