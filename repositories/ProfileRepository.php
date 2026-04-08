<?php

namespace Repositories;

use Models\Profile;
use Services\Database;
use PDO;

class ProfileRepository
{
    private ?PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getFirst(): ?Profile
    {
        $stmt = $this->db->query("SELECT p.*, i.url as image_url, i.alt as image_alt FROM profile p LEFT JOIN images i ON p.id_image = i.id LIMIT 1");
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $profile = new Profile();
        if ($data) {
            $profile->full_name = $data['full_name'] ?? '';
            $profile->email = $data['email'] ?? '';
            $profile->phone_number = (string)($data['phone_number'] ?? '');
            $profile->description = $data['description'] ?? '';
            $profile->skills = $data['skills'] ?? '';
            $profile->id_image = !empty($data['id_image']) ? (int)$data['id_image'] : null;
            $profile->image_url = $data['image_url'] ?? null;
            $profile->image_alt = $data['image_alt'] ?? null;
        }
        return $profile;
    }

    public function update(string $fullname, string $email, string $phone, string $description, string $skills)
    {
        // Check if a profile exists
        $stmt = $this->db->query("SELECT COUNT(*) FROM profile");
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $stmt = $this->db->prepare("UPDATE profile SET full_name=?, email=?, phone_number=?, description=?, skills=?");
            return $stmt->execute([$fullname, $email, $phone, $description, $skills]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO profile (full_name, email, phone_number, description, skills) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([$fullname, $email, $phone, $description, $skills]);
        }
    }
}
