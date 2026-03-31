<?php
/*
Voir les emails de développement
*/
$logFile = __DIR__ . '/logs/emails_dev.log';

echo "=== EMAILS DE DÉVELOPPEMENT ===\n\n";

if (file_exists($logFile)) {
    $content = file_get_contents($logFile);
    if (empty($content)) {
        echo "📭 Aucun email enregistré pour le moment.\n\n";
        echo "💡 Testez l'envoi d'email via l'interface web pour voir les logs ici.\n";
    } else {
        echo $content;
    }
} else {
    echo "📭 Fichier de log non trouvé.\n\n";
    echo "💡 Les emails de développement seront enregistrés ici automatiquement.\n";
}

echo "\n=== INSTRUCTIONS ===\n";
echo "1. Allez sur /admin/request-access\n";
echo "2. Remplissez et envoyez le formulaire\n";
echo "3. Revenez ici pour voir l'email 'envoyé'\n";
echo "\n=== FICHIERS DE LOG ===\n";
echo "📄 logs/emails_dev.log - Emails de développement\n";
echo "📄 Logs PHP standards - error_log()\n";
?>