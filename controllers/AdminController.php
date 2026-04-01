<?php

namespace Controllers;

use Services\Controller;
use Services\Session;
use Repositories\ProjectRepository;
use Repositories\SkillRepository;
use Repositories\ProfileRepository;
use Repositories\CategoryRepository;

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

    public function projects() {
        $projectRepo = new ProjectRepository();
        $projects = $projectRepo->findAll();
        $this->render('admin/projects', ['projects' => $projects]);
    }

    public function createProject() {
        $categoryRepo = new \Repositories\CategoryRepository();
        $categories = $categoryRepo->findAll();

        $skillRepo = new SkillRepository();
        $skills = $skillRepo->findAll();

        $this->render('admin/project_form', [
            'action' => '/admin/projects/store',
            'categories' => $categories,
            'project' => null,
            'skills' => $skills
        ]);
    }

    public function storeProject() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $labels = $_POST['labels'] ?? '';
            $link = $_POST['link'] ?? '';
            $id_category = $_POST['id_category'] ?? null;

            $projectRepo = new ProjectRepository();
            $projectRepo->create($title, $description, $start_date, $end_date, $labels, $link, $id_category);
            Session::setFlash('success', 'Projet ajouté avec succès.');
        }
        $this->redirect('/admin/projects');
    }

    public function editProject() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/projects');
            return;
        }

        $projectRepo = new ProjectRepository();
        $project = $projectRepo->findById((int) $id);

        $categoryRepo = new \Repositories\CategoryRepository();
        $categories = $categoryRepo->findAll();

        $skillRepo = new SkillRepository();
        $skills = $skillRepo->findAll();

        $this->render('admin/project_form', [
            'action' => '/admin/projects/update',
            'project' => $project,
            'categories' => $categories,
            'skills' => $skills
        ]);
    }

    public function updateProject() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                $this->redirect('/admin/projects');
                return;
            }

            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $labels = $_POST['labels'] ?? '';
            $link = $_POST['link'] ?? '';
            $id_category = $_POST['id_category'] ?? null;

            $projectRepo = new ProjectRepository();
            $projectRepo->update((int) $id, $title, $description, $start_date, $end_date, $labels, $link, $id_category);
            Session::setFlash('success', 'Projet mis à jour avec succès.');
        }
        $this->redirect('/admin/projects');
    }

    public function deleteProject() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $projectRepo = new ProjectRepository();
            $projectRepo->delete((int) $id);
            Session::setFlash('success', 'Projet supprimé.');
        }
        $this->redirect('/admin/projects');
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
