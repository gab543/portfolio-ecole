<?php

namespace Controllers;

use Services\Controller;
use Services\Session;
use Services\Security;
use Services\Database;
use Services\Mail\Mailer;
use Services\Mail\AccessRequestService;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use \Mailjet\Resources;

class AuthController extends Controller {
    
    public function login() {
        if (Session::isLoggedIn()) {
            $this->redirect('/admin');
        }
        $this->render('admin/login');
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/login');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM admins WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && Security::verifyPassword($password, $admin['password'])) {
            Session::login($email);
            Session::setFlash('success', 'Connexion réussie.');
            $this->redirect('/admin');
        } else {
            Session::setFlash('error', 'Identifiants incorrects.');
            $this->redirect('/admin/login');
        }
    }

    public function logout() {
        Session::logout();
        $this->redirect('/admin/login');
    }

    private function getMailConfig(): array {
        $settings = require __DIR__ . '/../configs/settings.php';
        return $settings['mail'] ?? [];
    }

    private function sendMail(string $to, string $subject, string $body, string $fromEmail, string $fromName = 'Portfolio') {
        $mailConfig = $this->getMailConfig();
        $provider = $mailConfig['provider'] ?? 'mailhog';

        // En développement local avec mailhog, logger les emails au lieu de les envoyer
        // Mais si mailjet_smtp est explicitement configuré, envoyer vraiment les emails
        if ($provider === 'mailhog' && ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['SERVER_NAME'] === 'localhost')) {
            return $this->sendMailDev($to, $subject, $body, $fromEmail, $fromName);
        }

        if ($provider === 'mailjet' && !empty($mailConfig['mailjet']['api_key']) && !empty($mailConfig['mailjet']['secret_key'])) {
            return $this->sendMailjet($to, $subject, $body, $fromEmail, $fromName, $mailConfig);
        } elseif ($provider === 'mailjet_smtp') {
            return $this->sendMailjetSMTP($to, $subject, $body, $fromEmail, $fromName, $mailConfig);
        } else {
            return $this->sendMailPHPMailer($to, $subject, $body, $fromEmail, $fromName, $mailConfig);
        }
    }

    private function sendMailDev(string $to, string $subject, string $body, string $fromEmail, string $fromName) {
        // Mode développement : logger l'email au lieu de l'envoyer
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

        // Créer aussi un fichier de log dédié pour les emails
        $logFile = __DIR__ . '/../logs/emails_dev.log';
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        file_put_contents($logFile, $logMessage, FILE_APPEND);

        return true; // Simuler un envoi réussi
    }

    private function sendMailjet(string $to, string $subject, string $body, string $fromEmail, string $fromName, array $mailConfig) {
        try {
            $mj = new \Mailjet\Client($mailConfig['mailjet']['api_key'], $mailConfig['mailjet']['secret_key'], true, ['version' => 'v3']);

            // For local development, you might need to disable SSL verification
            // In production, ensure proper SSL certificates are available
            $mj->setConnectionTimeout(30);

            // Create a plain text version from HTML (basic conversion)
            $textBody = strip_tags($body);
            $textBody = html_entity_decode($textBody, ENT_QUOTES, 'UTF-8');

            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => $fromEmail,
                            'Name' => $fromName
                        ],
                        'To' => [
                            [
                                'Email' => $to,
                                'Name' => $to
                            ]
                        ],
                        'Subject' => $subject,
                        'TextPart' => $textBody,
                        'HTMLPart' => $body
                    ]
                ]
            ];

            $response = $mj->post(Resources::$Email, ['body' => $body]);

            if ($response->success()) {
                return true;
            } else {
                error_log('Mailjet API error: ' . json_encode($response->getData()));
                return false;
            }
        } catch (\Exception $e) {
            error_log('Mailjet exception: ' . $e->getMessage());
            return false;
        }
    }

    private function sendMailjetSMTP(string $to, string $subject, string $body, string $fromEmail, string $fromName, array $mailConfig) {
        $smtpConfig = $mailConfig['mailjet_smtp'] ?? [];

        $host = $smtpConfig['host'] ?? 'in-v3.mailjet.com';
        $port = $smtpConfig['port'] ?? 587;
        $smtpAuth = $smtpConfig['smtp_auth'] ?? true;
        $smtpSecure = $smtpConfig['smtp_secure'] ?? 'tls';
        $user = $smtpConfig['username'] ?? '';
        $pass = $smtpConfig['password'] ?? '';

        $mail = new PHPMailer(true);

        try {
            // Server settings for Mailjet SMTP
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
            $mail->SMTPAutoTLS = true;

            // Recipients
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mailjet SMTP error: ' . $mail->ErrorInfo . ' | Exception: ' . $e->getMessage());
            return false;
        }
    }

    private function sendMailPHPMailer(string $to, string $subject, string $body, string $fromEmail, string $fromName, array $mailConfig) {
        $host = $mailConfig['host'] ?? 'localhost';
        $port = $mailConfig['port'] ?? 1025;
        $smtpAuth = $mailConfig['smtp_auth'] ?? false;
        $smtpSecure = $mailConfig['smtp_secure'] ?? '';
        $user = $mailConfig['username'] ?? '';
        $pass = $mailConfig['password'] ?? '';

        $mail = new PHPMailer(true);

        try {
            // Server settings
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

            // Recipients
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('PHPMailer error: ' . $mail->ErrorInfo . ' | Exception: ' . $e->getMessage());
            return false;
        }
    }

    public function requestAccess() {
        $this->render('admin/request_access');
    }

    public function submitRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/request-access');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $message = $_POST['message'] ?? '';

        if ($password !== $confirmPassword) {
            Session::setFlash('error', 'Les mots de passe ne correspondent pas.');
            $this->redirect('/admin/request-access');
        }

        $passwordHash = Security::hashPassword($password);

        $settings = require __DIR__ . '/../configs/settings.php';
        $mailConfig = $settings['mail'] ?? [];

        $mailer = new Mailer($mailConfig);
        $accessRequestService = new AccessRequestService($mailer, $mailConfig);

        $token = $accessRequestService->generateToken($email, $passwordHash);
        $confirmLink = $accessRequestService->getConfirmLink($token);

        if ($accessRequestService->sendRequestNotification($email, $message, $confirmLink)) {
            Session::setFlash('success', 'Votre demande a bien été envoyée à l\'administrateur.');
        } else {
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $fromEmail = $mailConfig['from'] ?? ('no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
            $headers .= "From: " . $fromEmail . "\r\n";

            if (mail($mailConfig['admin'] ?? 'admin@localhost', 'Nouvelle demande d\'acces au Portfolio', $accessRequestService->buildRequestBody($email, $message, $confirmLink), $headers)) {
                Session::setFlash('success', 'Votre demande a bien été envoyée à l\'administrateur (fallback).');
            } else {
                Session::setFlash('error', 'L\'envoi d\'email a échoué. Vérifiez la configuration mail (MailHog ou Mailjet).');
                error_log('CONFIRMATION LINK: ' . $confirmLink);
            }
        }

        $this->redirect('/admin/request-access');
    }

    public function confirmAccess() {
        $token = $_GET['token'] ?? '';
        if (!$token) {
            Session::setFlash('error', 'Lien invalide.');
            $this->redirect('/admin/login');
        }

        $decoded = json_decode(base64_decode($token), true);
        if (!$decoded || !isset($decoded['payload']) || !isset($decoded['signature'])) {
            Session::setFlash('error', 'Le jeton de sécurité est corrompu.');
            $this->redirect('/admin/login');
        }

        $secret = 'portfolio_secret_key_123!';
        $expectedSignature = hash_hmac('sha256', $decoded['payload'], $secret);

        if (!hash_equals($expectedSignature, $decoded['signature'])) {
            Session::setFlash('error', 'La signature du lien est invalide.');
            $this->redirect('/admin/login');
        }

        $data = json_decode($decoded['payload'], true);
        if (!$data || !isset($data['email']) || !isset($data['password']) || !isset($data['expires'])) {
            Session::setFlash('error', 'Données du lien invalides.');
            $this->redirect('/admin/login');
        }

        if (time() > $data['expires']) {
            Session::setFlash('error', 'Ce lien a expiré (validité de 24h).');
            $this->redirect('/admin/login');
        }

        $email = $data['email'];
        $passwordHash = $data['password'];

        $db = Database::getConnection();
        
        // Check if user already exists
        $stmtCheck = $db->prepare('SELECT id FROM admins WHERE email = ?');
        $stmtCheck->execute([$email]);
        if ($stmtCheck->fetch()) {
            Session::setFlash('error', 'Ce compte existe déjà.');
            $this->redirect('/admin/login');
        }

        // Create the user
        $stmtInsert = $db->prepare('INSERT INTO admins (email, password) VALUES (?, ?)');
        $stmtInsert->execute([$email, $passwordHash]);

        Session::setFlash('success', 'Le compte a été créé et activé avec succès. Vous pouvez maintenant vous connecter.');
        $this->redirect('/admin/login');
    }
}
