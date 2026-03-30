<?php

namespace Services;

class Security {
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public static function escape(string $input): string {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
