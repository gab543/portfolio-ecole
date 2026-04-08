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
            "WHERE pi.id_project = ? " .
            "ORDER BY pi.id ASC"
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
        // Supprimer d'abord les liaisons pour éviter les erreurs de clés étrangères
        $stmt = $this->db->prepare("DELETE FROM \"project images\" WHERE id_project = ?");
        $stmt->execute([$id]);

        $stmt = $this->db->prepare("DELETE FROM projects WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function insertImage(string $url, string $alt): int
    {
        $stmt = $this->db->prepare("INSERT INTO images (url, alt, is_cover) VALUES (?, ?, false)");
        $stmt->execute([$url, $alt]);
        
        $isPgsql = $this->db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql';
        return (int) $this->db->lastInsertId($isPgsql ? 'images_id_seq' : null);
    }

    public function syncImages(int $projectId, array $orderedImageIds): void
    {
        // 1. Supprimer l'ordre actuel pour ce projet
        $stmt = $this->db->prepare("DELETE FROM \"project images\" WHERE id_project = ?");
        $stmt->execute([$projectId]);

        $isPgsql = $this->db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql';

        // 2. Réinsérer dans l'ordre du tableau
        $first = true;
        foreach ($orderedImageIds as $imageId) {
            $stmt = $this->db->prepare("INSERT INTO \"project images\" (id_project, id_image) VALUES (?, ?)");
            $stmt->execute([$projectId, $imageId]);

            // Mettre à jour is_cover
            // pgsql utilise true/false boolean strings natively via PDO en interpolation facile pour ces cas ou PDO::PARAM_BOOL.
            // On gère mysql/pgsql de façon sûre.
            $coverVal = $first ? ($isPgsql ? 'true' : 1) : ($isPgsql ? 'false' : 0);
            $stmt = $this->db->prepare("UPDATE images SET is_cover = {$coverVal} WHERE id = ?");
            $stmt->execute([$imageId]);

            $first = false;
        }
    }
}
