<?php

namespace Services;

abstract class Controller {
    
    /**
     * Render a view file
     */
    protected function render(string $view, array $data = []) {
        extract($data);
        
        ob_start();
        require_once __DIR__ . '/../views/' . $view . '.phtml';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.phtml';
    }

    /**
     * Redirect to another path
     */
    protected function redirect(string $path) {
        header("Location: " . $path);
        exit();
    }
}
