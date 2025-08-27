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

    public function getAssetsByType($type, $slotCount = null)
    {
        $sql = "SELECT * FROM assets WHERE type = :type";
        if ($slotCount !== null) {
            $sql .= " AND slot_count = :slot_count";
        }
        $sql .= " ORDER BY created_at DESC";
        
        $this->db->query($sql);
        $this->db->bind(':type', $type);
        if ($slotCount !== null) {
            $this->db->bind(':slot_count', $slotCount);
        }
        
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

    public function updateFrameData($id, $data)
    {
        $this->db->query("UPDATE assets SET slot_count = :slot_count, slot_coordinates = :slot_coordinates WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':slot_count', $data['slot_count']);
        $this->db->bind(':slot_coordinates', $data['slot_coordinates']);
        return $this->db->execute();
    }
}