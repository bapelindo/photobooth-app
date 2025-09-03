<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Package
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM packages ORDER BY price ASC");
        return $this->db->resultSet();
    }

    public function find($id)
    {
        $this->db->query("SELECT * FROM packages WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create($data)
    {
        $this->db->query("INSERT INTO packages (name, description, price, photo_limit, frame_count, session_time_limit, photo_shot_limit, retake_limit) VALUES (:name, :description, :price, :photo_limit, :frame_count, :session_time_limit, :photo_shot_limit, :retake_limit)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':photo_limit', $data['photo_limit']);
        $this->db->bind(':frame_count', $data['frame_count']);
        $this->db->bind(':session_time_limit', $data['session_time_limit']);
        $this->db->bind(':photo_shot_limit', $data['photo_shot_limit']);
        $this->db->bind(':retake_limit', $data['retake_limit']);
        return $this->db->execute();
    }

    public function update($id, $data)
    {
        $this->db->query("UPDATE packages SET name = :name, description = :description, price = :price, photo_limit = :photo_limit, frame_count = :frame_count, session_time_limit = :session_time_limit, photo_shot_limit = :photo_shot_limit, retake_limit = :retake_limit WHERE id = :id");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':photo_limit', $data['photo_limit']);
        $this->db->bind(':frame_count', $data['frame_count']);
        $this->db->bind(':session_time_limit', $data['session_time_limit']);
        $this->db->bind(':photo_shot_limit', $data['photo_shot_limit']);
        $this->db->bind(':retake_limit', $data['retake_limit']);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM packages WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getPopularPackages($limit = 3)
    {
        $this->db->query("
            SELECT p.*, COUNT(t.id) as transaction_count
            FROM packages p
            JOIN transactions t ON p.id = t.package_id
            WHERE t.payment_status = 'success'
            GROUP BY p.id
            ORDER BY transaction_count DESC
            LIMIT :limit
        ");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }
}