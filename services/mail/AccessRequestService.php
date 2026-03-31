<?php

namespace Services\Mail;

use Services\Security;

class AccessRequestService {

    private Mailer $mailer;
    private array $mailConfig;

    public function __construct(Mailer $mailer, array $mailConfig = []) {
        $this->mailer = $mailer;
        $this->mailConfig = $mailConfig;
    }

    public function generateToken(string $email, string $passwordHash, int $ttlSeconds = 86400): string {
        $payload = json_encode([
            'email' => $email,
            'password' => $passwordHash,
            'expires' => time() + $ttlSeconds,
        ]);

        $secret = $this->mailConfig['token_secret'] ?? 'portfolio_secret_key_123!';
        $signature = hash_hmac('sha256', $payload, $secret);

        return base64_encode(json_encode(['payload' => $payload, 'signature' => $signature]));
    }

    public function getConfirmLink(string $token): string {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? '') == 443) ? 'https://' : 'http://';
        $domainName = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . $domainName . '/admin/confirm-access?token=' . urlencode($token);
    }

    public function buildRequestBody(string $requesterEmail, string $message, string $confirmLink): string {
        return "<html>\n<head>\n  <title>Demande d'accès admin</title>\n</head>\n<body>\n  <p>Une nouvelle demande d'accès a été formulée.</p>\n  <p><strong>Email:</strong> " . htmlspecialchars($requesterEmail) . "</p>\n  <p><strong>Message:</strong><br/>" . nl2br(htmlspecialchars($message)) . "</p>\n  <p><a href='" . $confirmLink . "' style='display:inline-block;padding:10px 20px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;'>Accepter et créer le compte</a></p>\n</body>\n</html>";
    }

    public function sendRequestNotification(string $requesterEmail, string $message, string $confirmLink): bool {
        $body = $this->buildRequestBody($requesterEmail, $message, $confirmLink);

        $to = $this->mailConfig['admin'] ?? 'admin@localhost';
        $fromEmail = $this->mailConfig['from'] ?? 'no-reply@localhost';
        $fromName = $this->mailConfig['from_name'] ?? 'Portfolio Admin';
        $subject = 'Nouvelle demande d\'acces au Portfolio';

        return $this->mailer->send($to, $subject, $body, $fromEmail, $fromName);
    }
}
