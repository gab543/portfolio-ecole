<?php

namespace Controllers;

use Services\Controller;
use Repositories\ProjectRepository;
use Repositories\ProfileRepository;
use Repositories\SkillRepository;
use Repositories\CategoryRepository;

class FrontController extends Controller {
    
    public function home() {
        $projectRepo = new ProjectRepository();
        $projects = $projectRepo->findAll();

        $profileRepo = new ProfileRepository();
        $profile = $profileRepo->getFirst();

        $skillRepo = new SkillRepository();
        $skills = $skillRepo->findAll();

        $categoryRepo = new CategoryRepository();
        $categories = $categoryRepo->findAll();

        $this->render('home', [
            'projects' => $projects,
            'profile' => $profile,
            'skills' => $skills,
            'categories' => $categories
        ]);
    }

    public function projectDetail() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->redirect('/');
        }

        $id = (int) $_GET['id'];
        $projectRepo = new ProjectRepository();
        $project = $projectRepo->findById($id);

        if (!$project) {
            // Send to 404
            http_response_code(404);
            $this->render('404');
            exit();
        }

        $this->render('project_detail', [
            'project' => $project,
            'category' => $projectRepo->getCategory($project->category_id),
            'images' => $projectRepo->getImages($project->id)
        ]);
    }
}
