<?php

namespace App\Core;

use PDO;
use Services\Database;

abstract class Model
{
    protected ?PDO $db;
    protected string $table;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Get all records from the table
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    /**
     * Get a record by ID
     */
    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Delete a record by ID
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
