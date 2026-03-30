<?php

namespace Repositories;

use Models\Profile;
use Services\Database;
use PDO;

class ProfileRepository {
    private ?PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getFirst(): ?Profile {
        $stmt = $this->db->query("SELECT * FROM profile LIMIT 1");
        $stmt->setFetchMode(PDO::FETCH_CLASS, Profile::class);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function update(string $fullname, string $email, string $phone, string $description) {
        $stmt = $this->db->prepare("UPDATE profile SET full_name=?, email=?, phone_number=?, description=?");
        return $stmt->execute([$fullname, $email, $phone, $description]);
    }
}
