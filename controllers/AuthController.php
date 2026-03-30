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
}
