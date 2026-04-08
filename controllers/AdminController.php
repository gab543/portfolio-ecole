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

        $skillRepo = new SkillRepository();
        $skills = $skillRepo->findAll();

        $this->render('admin/profile', [
            'profile' => $profile,
            'skills' => $skills
        ]);
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
            $pdo = \Services\Database::getConnection();
            $isPgsql = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME) === 'pgsql';
            $projectId = (int) $pdo->lastInsertId($isPgsql ? 'projects_id_seq' : null);

            $this->handleImageUpload($projectId, $projectRepo);

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
        $projectImages = $projectRepo->getImages((int) $id);

        $categoryRepo = new \Repositories\CategoryRepository();
        $categories = $categoryRepo->findAll();

        $skillRepo = new SkillRepository();
        $skills = $skillRepo->findAll();

        $this->render('admin/project_form', [
            'action' => '/admin/projects/update',
            'project' => $project,
            'projectImages' => $projectImages,
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
            
            $this->handleImageUpload((int) $id, $projectRepo);

            Session::setFlash('success', 'Projet mis à jour avec succès.');
        }
        $this->redirect('/admin/projects');
    }

    private function handleImageUpload(int $projectId, ProjectRepository $projectRepo) {
        $orderedImageIds = [];
        $orderInfo = isset($_POST['image_order']) ? json_decode($_POST['image_order'], true) : [];

        // Itérer l'ordre envoyé par le client (JS)
        if (is_array($orderInfo)) {
            foreach ($orderInfo as $item) {
                if (strpos($item, 'existing_') === 0) {
                    $imageId = (int) substr($item, 9);
                    $orderedImageIds[] = $imageId;
                } elseif (strpos($item, 'new_') === 0) {
                    $index = (int) substr($item, 4);
                    // Upload le fichier coorespondant dans $_FILES['new_files']
                    if (isset($_FILES['new_files']['name'][$index]) && $_FILES['new_files']['error'][$index] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['new_files']['tmp_name'][$index];
                        $name = basename($_FILES['new_files']['name'][$index]);
                        $targetName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $name);
                        $uploadDir = __DIR__ . '/../public/upload/';
                        
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $targetFile = $uploadDir . $targetName;

                        if (move_uploaded_file($tmpName, $targetFile)) {
                            // On insère l'image et on récupère son ID avant de la garder pour le sync
                            $newImageId = $projectRepo->insertImage($targetName, 'Image pour ' . $projectId);
                            if ($newImageId) {
                                $orderedImageIds[] = $newImageId;
                            }
                        }
                    }
                }
            }
        }

        // Si aucun JS n'a intercepté, fallback pour upload standard multi (image[])
        if (empty($orderInfo) && isset($_FILES['image']) && is_array($_FILES['image']['name'])) {
            foreach ($_FILES['image']['name'] as $idx => $name) {
                if ($_FILES['image']['error'][$idx] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['image']['tmp_name'][$idx];
                    $targetName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($name));
                    $uploadDir = __DIR__ . '/../public/upload/';
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    if (move_uploaded_file($tmpName, $uploadDir . $targetName)) {
                        $newId = $projectRepo->insertImage($targetName, 'Image pour ' . $projectId);
                        if ($newId) $orderedImageIds[] = $newId;
                    }
                }
            }
            
            // On veut aussi garder les images qui existaient déjà dans la BDD pour ce paramétrage au fallback
            $existing = $projectRepo->getImages($projectId);
            foreach ($existing as $img) {
                if (!in_array($img['id'], $orderedImageIds)) {
                     // on met les nouvelles en premier, les anciennes après
                     $orderedImageIds[] = $img['id'];
                }
            }
        }

        $projectRepo->syncImages($projectId, $orderedImageIds);
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
            $description = trim($_POST['description'] ?? '');
            $fullname = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone_number'] ?? '');
            $skills = $_POST['labels'] ?? ''; // Matches the hidden input from adminLabels.js

            // Error Management / Validation
            if (empty($fullname) || empty($email)) {
                Session::setFlash('error', 'Le nom et l\'email sont obligatoires.');
                $this->redirect('/admin/profile');
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Session::setFlash('error', 'Le format de l\'adresse email est invalide.');
                $this->redirect('/admin/profile');
                return;
            }

            try {
                $profileRepo = new ProfileRepository();
                $profileRepo->update($fullname, $email, $phone, $description, $skills);
                Session::setFlash('success', 'Profil mis à jour avec succès.');
            } catch (\Exception $e) {
                Session::setFlash('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
            }
        }
        $this->redirect('/admin/profile');
    }
}
