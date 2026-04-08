<?php

namespace Repositories;

use Models\Project;
use Models\Category;
use Services\Database;
use PDO;

class ProjectRepository
{
    private ?PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT p.*,
                   (SELECT i.url FROM \"project images\" pi JOIN \"images\" i ON pi.id_image = i.id WHERE pi.id_project = p.id ORDER BY i.is_cover DESC LIMIT 1) as image_url,
                   (SELECT i.alt FROM \"project images\" pi JOIN \"images\" i ON pi.id_image = i.id WHERE pi.id_project = p.id ORDER BY i.is_cover DESC LIMIT 1) as image_alt
            FROM projects p
        ");
        //le FETCH_CLASS permet de return des objets
        //Project::class est le nom de la classe
        return $stmt->fetchAll(PDO::FETCH_CLASS, Project::class);
    }

    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->setFetchMode(PDO::FETCH_CLASS, Project::class);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getCategory(?int $categoryId): ?Category
    {
        if (!$categoryId)
            return null;
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->setFetchMode(PDO::FETCH_CLASS, Category::class);
        $stmt->execute([$categoryId]);
        $cat = $stmt->fetch();
        return $cat ?: null;
    }

    public function getImages(int $projectId): array
    {
        if (!$projectId)
            return [];

        // La table "images" n'a pas de colonne project_id; la liaison se fait via "project images".
        $stmt = $this->db->prepare(
            "SELECT i.* FROM \"images\" i " .
            "JOIN \"project images\" pi ON pi.id_image = i.id " .
            "WHERE pi.id_project = ?"
        );
        $stmt->execute([$projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $title, string $description, string $start_date, string $end_date, string $labels, string $link, ?int $categoryId): bool
    {
        $stmt = $this->db->prepare("INSERT INTO projects (title, description, start_date, end_date, labels, link, id_category) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $description, $start_date, $end_date, $labels, $link, $categoryId]);
    }

    public function update(int $id, string $title, string $description, string $start_date, string $end_date, string $labels, string $link, ?int $categoryId): bool
    {
        $stmt = $this->db->prepare("UPDATE projects SET title = ?, description = ?, start_date = ?, end_date = ?, labels = ?, link = ?, id_category = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $start_date, $end_date, $labels, $link, $categoryId, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM projects WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
