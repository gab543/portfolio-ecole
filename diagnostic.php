<?php
/*
Complete email configuration diagnostic
*/
require 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== DIAGNOSTIC COMPLET DE CONFIGURATION EMAIL ===\n\n";

$issues = [];
$warnings = [];

// 1. Check environment variables
echo "1. Variables d'environnement (.env) :\n";
$mailProvider = $_ENV['MAIL_PROVIDER'] ?? 'mailhog';
$apiKey = $_ENV['API_MAIL'] ?? '';
$secretKey = $_ENV['SECRET_API_MAIL'] ?? '';

echo "   MAIL_PROVIDER: $mailProvider\n";
echo "   API_MAIL: " . (!empty($apiKey) ? substr($apiKey, 0, 8) . "..." : "❌ NON CONFIGURÉ") . "\n";
echo "   SECRET_API_MAIL: " . (!empty($secretKey) ? substr($secretKey, 0, 8) . "..." : "❌ NON CONFIGURÉ") . "\n";

if (empty($apiKey) || empty($secretKey)) {
    $issues[] = "Clés API Mailjet manquantes dans .env";
}
if ($mailProvider !== 'mailjet_smtp') {
    $issues[] = "MAIL_PROVIDER devrait être 'mailjet_smtp' pour le développement local";
}

echo "\n";

// 2. Check settings.php configuration
echo "2. Configuration settings.php :\n";
$settings = require __DIR__ . '/configs/settings.php';
$mailConfig = $settings['mail'] ?? [];

$fromEmail = $mailConfig['from'] ?? '';
$adminEmail = $mailConfig['admin'] ?? '';

echo "   From Email: $fromEmail\n";
echo "   Admin Email: $adminEmail\n";

if (strpos($fromEmail, 'example.com') !== false || strpos($fromEmail, 'localhost') !== false) {
    $issues[] = "Adresse email expéditeur non configurée (encore 'test@example.com' ou localhost)";
}
if (strpos($adminEmail, 'example.com') !== false || strpos($adminEmail, 'localhost') !== false) {
    $issues[] = "Adresse email admin non configurée (encore 'test@example.com' ou localhost)";
}

// 3. Check SMTP configuration
echo "\n3. Configuration SMTP :\n";
$smtpConfig = $mailConfig['mailjet_smtp'] ?? [];
echo "   Host: " . ($smtpConfig['host'] ?? 'N/A') . "\n";
echo "   Port: " . ($smtpConfig['port'] ?? 'N/A') . "\n";
echo "   SMTP Auth: " . (($smtpConfig['smtp_auth'] ?? false) ? 'Oui' : 'Non') . "\n";
echo "   SMTP Secure: " . ($smtpConfig['smtp_secure'] ?? 'N/A') . "\n";

// 4. Test API connection
echo "\n4. Test de connexion API Mailjet :\n";
if (!empty($apiKey) && !empty($secretKey)) {
    try {
        $mj = new \Mailjet\Client($apiKey, $secretKey, true, ['version' => 'v3']);
        $response = $mj->get(\Mailjet\Resources::$Apikey);

        if ($response->success()) {
            echo "   ✅ Connexion API réussie\n";
            $data = $response->getData();
            if (is_array($data) && isset($data[0]['Name'])) {
                echo "   Compte: " . $data[0]['Name'] . "\n";
            }
        } else {
            echo "   ❌ Échec connexion API (Status: " . $response->getStatus() . ")\n";
            $issues[] = "Connexion API Mailjet échoue";
        }
    } catch (Exception $e) {
        echo "   ❌ Exception API: " . $e->getMessage() . "\n";
        $issues[] = "Exception lors de la connexion API: " . $e->getMessage();
    }
} else {
    echo "   ❌ Impossible de tester l'API (clés manquantes)\n";
}

// 5. Summary
echo "\n=== RÉSUMÉ ===\n";

if (empty($issues)) {
    echo "✅ Configuration semble correcte !\n";
    echo "\n📋 Actions restantes :\n";
    echo "1. Vérifiez que votre adresse email est validée dans le dashboard Mailjet\n";
    echo "2. Testez l'envoi d'email via l'interface web (/admin/request-access)\n";
} else {
    echo "❌ Problèmes détectés :\n";
    foreach ($issues as $issue) {
        echo "   - $issue\n";
    }

    echo "\n🔧 Actions à effectuer :\n";
    if (in_array("Adresse email expéditeur non configurée (encore 'test@example.com' ou localhost)", $issues)) {
        echo "1. Modifiez configs/settings.php :\n";
        echo "   - 'from' => 'votre-email-verifie@domain.com'\n";
        echo "   - 'admin' => 'votre-email-admin@domain.com'\n";
    }
    if (in_array("MAIL_PROVIDER devrait être 'mailjet_smtp' pour le développement local", $issues)) {
        echo "2. Dans .env : MAIL_PROVIDER = \"mailjet_smtp\"\n";
    }
    if (in_array("Clés API Mailjet manquantes dans .env", $issues)) {
        echo "3. Ajoutez dans .env :\n";
        echo "   API_MAIL=votre_cle_api\n";
        echo "   SECRET_API_MAIL=votre_cle_secrete\n";
    }
    echo "4. Vérifiez votre email dans le dashboard Mailjet (Account > Sender addresses)\n";
}

echo "\n=== FICHIERS À VÉRIFIER ===\n";
echo "📄 .env - Variables d'environnement\n";
echo "📄 configs/settings.php - Configuration email\n";
echo "🌐 Dashboard Mailjet - Validation des adresses email\n";

echo "\n=== TESTS DISPONIBLES ===\n";
echo "php check_config.php - Diagnostic rapide\n";
echo "php index.php - Test connexion API\n";
echo "/admin/request-access - Test envoi email via interface\n";
?>