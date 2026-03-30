<?php

namespace Services;

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setFlash(string $key, string $message) {
        self::start();
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash(string $key): ?string {
        self::start();
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    public static function isLoggedIn(): bool {
        self::start();
        return isset($_SESSION['user_email']);
    }
    
    public static function login(string $email) {
        self::start();
        $_SESSION['user_email'] = $email;
    }

    public static function logout() {
        self::start();
        unset($_SESSION['user_email']);
        session_destroy();
    }
}
