<?php

namespace Repositories;

use Models\Skill;
use Services\Database;
use PDO;

class SkillRepository {
    private ?PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM skills");
        return $stmt->fetchAll(PDO::FETCH_CLASS, Skill::class);
    }
}
