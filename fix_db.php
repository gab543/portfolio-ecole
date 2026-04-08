<?php

$settings = require __DIR__ . '/configs/settings.php';
$dbConf = $settings['database'];

$host = $dbConf['host'];
$db = $dbConf['dbname'];
$user = $dbConf['user'];
$pass = $dbConf['password'];
$port = $dbConf['port'] ?? '3306';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("ALTER TABLE profile ADD COLUMN skills VARCHAR(255) NOT NULL DEFAULT ''");
    echo "Successfully added 'skills' column to 'profile' table.\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
