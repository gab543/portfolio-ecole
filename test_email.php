<?php
/*
Quick email test after configuration
*/
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load configuration
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$settings = require __DIR__ . '/configs/settings.php';
$mailConfig = $settings['mail'] ?? [];
$smtpConfig = $mailConfig['mailjet_smtp'] ?? [];

$fromEmail = $mailConfig['from'] ?? '';
$apiKey = $_ENV['API_MAIL'] ?? '';
$secretKey = $_ENV['SECRET_API_MAIL'] ?? '';

echo "=== TEST RAPIDE ENVOI EMAIL ===\n\n";

$errors = [];

// Check configuration
if (empty($fromEmail) || strpos($fromEmail, 'example.com') !== false) {
    $errors[] = "Adresse email expéditeur non configurée";
}
if (empty($apiKey) || empty($secretKey)) {
    $errors[] = "Clés API Mailjet manquantes";
}

if (!empty($errors)) {
    echo "❌ Configuration incomplète :\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
    echo "\nConsultez EMAIL_SETUP_GUIDE.md pour les instructions.\n";
    exit(1);
}

echo "✅ Configuration détectée :\n";
echo "   Expéditeur: $fromEmail\n";
echo "   API Key: " . substr($apiKey, 0, 8) . "...\n\n";

echo "📧 Tentative d'envoi d'email de test...\n";

// Test email sending
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = $smtpConfig['host'] ?? 'in-v3.mailjet.com';
    $mail->Port = $smtpConfig['port'] ?? 587;
    $mail->SMTPAuth = true;
    $mail->Username = $apiKey;
    $mail->Password = $secretKey;
    $mail->SMTPSecure = $smtpConfig['smtp_secure'] ?? 'tls';
    $mail->SMTPAutoTLS = true;

    $mail->setFrom($fromEmail, $mailConfig['from_name'] ?? 'Portfolio');
    $mail->addAddress($fromEmail, 'Test Recipient'); // Send to same address for testing

    $mail->isHTML(true);
    $mail->Subject = 'Test Email - Portfolio Configuration';
    $mail->Body = '<h3>✅ Test Réussi !</h3><p>Votre configuration email fonctionne correctement.</p>';

    $mail->send();
    echo "✅ EMAIL ENVOYÉ AVEC SUCCÈS !\n\n";
    echo "📬 Vérifiez votre boîte mail pour le message de test.\n";
    echo "🎉 Votre configuration email est maintenant opérationnelle !\n";

} catch (Exception $e) {
    echo "❌ ÉCHEC DE L'ENVOI :\n";
    echo "   Erreur: " . $mail->ErrorInfo . "\n";
    echo "   Exception: " . $e->getMessage() . "\n\n";

    if (strpos($mail->ErrorInfo, 'sender') !== false || strpos($mail->ErrorInfo, 'verify') !== false) {
        echo "💡 Conseil : Vérifiez que votre adresse email est validée dans Mailjet\n";
        echo "   Dashboard Mailjet → Account → Sender addresses\n";
    }
}

echo "\n=== PROCHAINES ÉTAPES ===\n";
echo "1. Testez via l'interface web : /admin/request-access\n";
echo "2. Vérifiez les logs en cas de problème\n";
echo "3. Consultez EMAIL_SETUP_GUIDE.md pour plus de détails\n";
?>