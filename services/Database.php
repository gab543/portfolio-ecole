<?php

namespace Services;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $settings = require __DIR__ . '/../configs/settings.php';
            $dbConf = $settings['database'];

            $host = $dbConf['host'];
            $db = $dbConf['dbname'];
            $user = $dbConf['user'];
            $pass = $dbConf['password'];
            $port = $dbConf['port'] ?? '8080';

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
