<?php
/*
This call sends a message to one recipient.
Adapted from Mailjet documentation for this project.
*/
require 'vendor/autoload.php';

use \Mailjet\Resources;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get Mailjet credentials (same as working index.php)
$apiKey = $_ENV['API_MAIL'] ?? '';
$secretKey = $_ENV['SECRET_API_MAIL'] ?? '';

if (empty($apiKey) || empty($secretKey)) {
    die("Error: Mailjet API credentials not found in .env file\n");
}

$mj = new \Mailjet\Client($apiKey, $secretKey, true, ['version' => 'v3']);

$body = [
    'Messages' => [
        [
            'From' => [
                'Email' => "noreply@localhost", // Use a verified sender or localhost for testing
                'Name' => "Portfolio Contact"
            ],
            'To' => [
                [
                    'Email' => "test@localhost", // Use localhost for testing
                    'Name' => "Test User"
                ]
            ],
            'Subject' => "Test email from Portfolio",
            'TextPart' => "This is a test email from your Portfolio application.",
            'HTMLPart' => "<h3>Test Email</h3><p>This is a test email from your Portfolio application.</p>"
        ]
    ]
];

echo "Sending email with the following data:\n";
echo "From: " . $body['Messages'][0]['From']['Email'] . "\n";
echo "To: " . $body['Messages'][0]['To'][0]['Email'] . "\n";
echo "Subject: " . $body['Messages'][0]['Subject'] . "\n\n";

$response = $mj->post(Resources::$Email, ['body' => $body]);

if ($response->success()) {
    echo "✅ Email sent successfully!\n";
    var_dump($response->getData());
} else {
    echo "❌ Failed to send email\n";
    echo "Status: " . $response->getStatus() . "\n";

    if ($response->getStatus() == 400) {
        echo "\n💡 This is likely because the sender email is not verified in Mailjet.\n";
        echo "To fix this:\n";
        echo "1. Go to your Mailjet dashboard\n";
        echo "2. Verify your sender email address\n";
        echo "3. Or use the SMTP method for testing: set MAIL_PROVIDER=mailjet_smtp in .env\n";
    }

    var_dump($response->getData());
}
?>