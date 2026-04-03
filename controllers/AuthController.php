<?php

namespace Controllers;

use Services\Controller;
use Services\Session;
use Services\Security;
use Services\Database;

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

        // Create a stateless secure token
        $passwordHash = Security::hashPassword($password);
        
        // Data payload
        $payload = json_encode([
            'email' => $email,
            'password' => $passwordHash,
            'expires' => time() + (24 * 3600) // 24 hours validity
        ]);

        // We use a basic application secret. If none exists, we fallback to a hardcoded one for this feature
        $secret = 'portfolio_secret_key_123!';
        $signature = hash_hmac('sha256', $payload, $secret);

        $token = base64_encode(json_encode(['payload' => $payload, 'signature' => $signature]));

        // Construct email
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        $confirmLink = $protocol . $domainName . '/admin/confirm-access?token=' . urlencode($token);

        $to = 'gabriel.caboche@gmail.com'; // TODO: Replace with the exact admin email or dynamic config
        $subject = 'Nouvelle demande d\'acces au Portfolio';
        $body = "
            <html>
            <head>
              <title>Demande d'accès admin</title>
            </head>
            <body>
              <p>Une nouvelle demande d'accès a été formulée.</p>
              <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
              <p><strong>Message:</strong><br/>" . nl2br(htmlspecialchars($message)) . "</p>
              <p><a href='" . $confirmLink . "' style='display:inline-block;padding:10px 20px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;'>Accepter et créer le compte</a></p>
            </body>
            </html>
        ";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@" . $domainName . "\r\n";

        // Try to send mail
        if (mail($to, $subject, $body, $headers)) {
            Session::setFlash('success', 'Votre demande a bien été envoyée à l\'administrateur.');
        } else {
            // Note: On local MAMP without mail setup, mail() might fail. 
            // We flash success anyway for UX, or an error if they prefer knowing it failed.
            Session::setFlash('error', 'La fonction mail() a échoué. Assurez-vous que MailHog/MAMP est configuré.');
            
            // For testing purposes during dev if mail() fails, we log the link to error_log
            error_log("CONFIRMATION LINK: " . $confirmLink);
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
