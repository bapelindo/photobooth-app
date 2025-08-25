<?php

namespace App\Models;

use App\Core\Database;

class Asset
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM assets ORDER BY type, created_at DESC");
        return $this->db->resultSet();
    }

    public function getAssetsByType($type)
    {
        $this->db->query("SELECT * FROM assets WHERE type = :type ORDER BY created_at DESC");
        $this->db->bind(':type', $type);
        return $this->db->resultSet();
    }

    public function find($id)
    {
        $this->db->query("SELECT * FROM assets WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create($data)
    {
                $this->db->query("INSERT INTO assets (name, type, path) VALUES (:name, :type, :path)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':path', $data['file_path']);
        return $this->db->execute();
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM assets WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}