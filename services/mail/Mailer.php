<?php

namespace Services\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    private array $mailConfig;

    public function __construct(array $mailConfig = []) {
        $this->mailConfig = $mailConfig;
    }

    public function send(string $to, string $subject, string $htmlBody, string $fromEmail, string $fromName = 'Portfolio'): bool {
        $provider = $this->mailConfig['provider'] ?? 'mailhog';

        if ($provider === 'mailhog' && (($_SERVER['HTTP_HOST'] ?? '') === 'localhost' || ($_SERVER['SERVER_NAME'] ?? '') === 'localhost')) {
            return $this->sendDev($to, $subject, $htmlBody, $fromEmail, $fromName);
        }

        if ($provider === 'mailjet' && !empty($this->mailConfig['mailjet']['api_key']) && !empty($this->mailConfig['mailjet']['secret_key'])) {
            return $this->sendMailjet($to, $subject, $htmlBody, $fromEmail, $fromName);
        }

        if ($provider === 'mailjet_smtp') {
            return $this->sendMailjetSmtp($to, $subject, $htmlBody, $fromEmail, $fromName);
        }

        return $this->sendPhpmailer($to, $subject, $htmlBody, $fromEmail, $fromName);
    }

    private function sendDev(string $to, string $subject, string $body, string $fromEmail, string $fromName): bool {
        $logMessage = "\n" . str_repeat("=", 50) . "\n";
        $logMessage .= "📧 EMAIL DE DÉVELOPPEMENT (NON ENVOYÉ)\n";
        $logMessage .= str_repeat("=", 50) . "\n";
        $logMessage .= "De: $fromName <$fromEmail>\n";
        $logMessage .= "À: $to\n";
        $logMessage .= "Sujet: $subject\n";
        $logMessage .= "Date: " . date('Y-m-d H:i:s') . "\n";
        $logMessage .= str_repeat("-", 30) . "\n";
        $logMessage .= "Contenu:\n" . strip_tags($body) . "\n";
        $logMessage .= str_repeat("=", 50) . "\n";

        error_log($logMessage);

        $logFile = __DIR__ . '/../logs/emails_dev.log';
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        file_put_contents($logFile, $logMessage, FILE_APPEND);

        return true;
    }

    private function sendMailjet(string $to, string $subject, string $htmlBody, string $fromEmail, string $fromName): bool {
        try {
            $mj = new \Mailjet\Client(
                $this->mailConfig['mailjet']['api_key'],
                $this->mailConfig['mailjet']['secret_key'],
                true,
                ['version' => 'v3']
            );
            $mj->setConnectionTimeout(30);

            $textBody = strip_tags($htmlBody);
            $textBody = html_entity_decode($textBody, ENT_QUOTES, 'UTF-8');

            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => $fromEmail,
                            'Name' => $fromName,
                        ],
                        'To' => [[
                            'Email' => $to,
                            'Name' => $to,
                        ]],
                        'Subject' => $subject,
                        'TextPart' => $textBody,
                        'HTMLPart' => $htmlBody,
                    ],
                ],
            ];

            $response = $mj->post(\Mailjet\Resources::$Email, ['body' => $body]);

            if ($response->success()) {
                return true;
            }

            error_log('Mailjet API error: ' . json_encode($response->getData()));
            return false;
        } catch (\Exception $e) {
            error_log('Mailjet exception: ' . $e->getMessage());
            return false;
        }
    }

    private function sendMailjetSmtp(string $to, string $subject, string $htmlBody, string $fromEmail, string $fromName): bool {
        $smtpConfig = $this->mailConfig['mailjet_smtp'] ?? [];
        $host = $smtpConfig['host'] ?? 'in-v3.mailjet.com';
        $port = $smtpConfig['port'] ?? 587;
        $smtpAuth = $smtpConfig['smtp_auth'] ?? true;
        $smtpSecure = $smtpConfig['smtp_secure'] ?? 'tls';
        $user = $smtpConfig['username'] ?? '';
        $pass = $smtpConfig['password'] ?? '';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->Port = $port;
            $mail->SMTPAuth = $smtpAuth;
            if ($smtpAuth) {
                $mail->Username = $user;
                $mail->Password = $pass;
            }
            $mail->SMTPSecure = $smtpSecure;
            $mail->SMTPAutoTLS = true;

            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mailjet SMTP error: ' . $mail->ErrorInfo . ' | Exception: ' . $e->getMessage());
            return false;
        }
    }

    private function sendPhpmailer(string $to, string $subject, string $htmlBody, string $fromEmail, string $fromName): bool {
        $host = $this->mailConfig['host'] ?? 'localhost';
        $port = $this->mailConfig['port'] ?? 1025;
        $smtpAuth = $this->mailConfig['smtp_auth'] ?? false;
        $smtpSecure = $this->mailConfig['smtp_secure'] ?? '';
        $user = $this->mailConfig['username'] ?? '';
        $pass = $this->mailConfig['password'] ?? '';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->Port = $port;
            $mail->SMTPAuth = $smtpAuth;
            if ($smtpAuth) {
                $mail->Username = $user;
                $mail->Password = $pass;
            }
            if (!empty($smtpSecure)) {
                $mail->SMTPSecure = $smtpSecure;
            }
            $mail->SMTPAutoTLS = false;

            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('PHPMailer error: ' . $mail->ErrorInfo . ' | Exception: ' . $e->getMessage());
            return false;
        }
    }
}
