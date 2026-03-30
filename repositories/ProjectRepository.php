<?php

namespace Repositories;

use Models\Project;
use Models\Category;
use Services\Database;
use PDO;

class ProjectRepository {
    private ?PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM projects");
        return $stmt->fetchAll(PDO::FETCH_CLASS, Project::class);
    }

    public function findById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->setFetchMode(PDO::FETCH_CLASS, Project::class);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getCategory(int $categoryId): ?Category {
        if (!$categoryId) return null;
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->setFetchMode(PDO::FETCH_CLASS, Category::class);
        $stmt->execute([$categoryId]);
        $cat = $stmt->fetch();
        return $cat ?: null;
    }

    public function getImages(int $projectId): array {
        if (!$projectId) return [];
        $stmt = $this->db->prepare("SELECT * FROM images WHERE project_id = ?");
        $stmt->execute([$projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
