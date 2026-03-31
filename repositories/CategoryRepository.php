<?php

namespace Repositories;

use Models\Category;
use Services\Database;
use PDO;

class CategoryRepository {
    private ?PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_CLASS, Category::class);
    }
}
