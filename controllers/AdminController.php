<?php

namespace Controllers;

use Services\Controller;
use Services\Session;
use Repositories\ProjectRepository;
use Repositories\SkillRepository;
use Repositories\ProfileRepository;

class AdminController extends Controller {

    public function __construct() {
        if (!Session::isLoggedIn()) {
            if ($_SERVER['REQUEST_URI'] !== '/admin/login' && $_SERVER['REQUEST_URI'] !== '/admin/authenticate') {
                $this->redirect('/admin/login');
            }
        }
    }

    public function dashboard() {
        $projectRepo = new ProjectRepository();
        $projectsCount = count($projectRepo->findAll());

        $skillRepo = new SkillRepository();
        $skillsCount = count($skillRepo->findAll());

        $this->render('admin/dashboard', [
            'projectsCount' => $projectsCount,
            'skillsCount' => $skillsCount
        ]);
    }

    public function profile() {
        $profileRepo = new ProfileRepository();
        $profile = $profileRepo->getFirst();
        $this->render('admin/profile', ['profile' => $profile]);
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $description = $_POST['description'] ?? '';
            $fullname = $_POST['full_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone_number'] ?? '';

            $profileRepo = new ProfileRepository();
            $profileRepo->update($fullname, $email, $phone, $description);

            Session::setFlash('success', 'Profil mis à jour avec succès.');
        }
        $this->redirect('/admin/profile');
    }
}
