<?php
require_once __DIR__ . '/vendor/autoload.php';

use Services\Database;

try {
    $db = Database::getConnection();
    $db->exec("ALTER TABLE profile ADD COLUMN skills VARCHAR(255) DEFAULT ''");
    echo "Colonne 'skills' ajoutee avec succes ! Vous pouvez supprimer ce fichier.";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "La colonne 'skills' existe deja ! Vous pouvez supprimer ce fichier.";
    } else {
        echo "Erreur: " . $e->getMessage();
    }
}
