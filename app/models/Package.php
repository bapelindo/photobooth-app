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
        $this->db->query("INSERT INTO packages (name, description, price, photo_limit, photo_slots, retake_limit, frame_limit, session_duration, max_save_photos) VALUES (:name, :description, :price, :photo_limit, :photo_slots, :retake_limit, :frame_limit, :session_duration, :max_save_photos)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':photo_limit', $data['photo_limit']);
        $this->db->bind(':photo_slots', $data['photo_slots']);
        $this->db->bind(':retake_limit', $data['retake_limit']);
        $this->db->bind(':frame_limit', $data['frame_limit'] ?? 1);
        $this->db->bind(':session_duration', $data['session_duration'] ?? 300);
        $this->db->bind(':max_save_photos', $data['max_save_photos'] ?? 20);
        return $this->db->execute();
    }

    public function update($id, $data)
    {
        $this->db->query("UPDATE packages SET name = :name, description = :description, price = :price, photo_limit = :photo_limit, photo_slots = :photo_slots, retake_limit = :retake_limit, frame_limit = :frame_limit, session_duration = :session_duration, max_save_photos = :max_save_photos WHERE id = :id");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':photo_limit', $data['photo_limit']);
        $this->db->bind(':photo_slots', $data['photo_slots']);
        $this->db->bind(':retake_limit', $data['retake_limit']);
        $this->db->bind(':frame_limit', $data['frame_limit'] ?? 1);
        $this->db->bind(':session_duration', $data['session_duration'] ?? 300);
        $this->db->bind(':max_save_photos', $data['max_save_photos'] ?? 20);
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

    public function getPackagePerformance()
    {
        $this->db->query("
            SELECT p.*, 
                   COUNT(t.id) as total_sales,
                   SUM(CASE WHEN t.payment_status = 'success' THEN 1 ELSE 0 END) as successful_sales,
                   SUM(CASE WHEN t.payment_status = 'success' THEN t.amount ELSE 0 END) as total_revenue,
                   AVG(CASE WHEN t.payment_status = 'success' THEN ps.photos_saved ELSE NULL END) as avg_photos_saved
            FROM packages p
            LEFT JOIN transactions t ON p.id = t.package_id
            LEFT JOIN photo_sessions ps ON t.id = ps.transaction_id
            GROUP BY p.id
            ORDER BY total_revenue DESC
        ");
        return $this->db->resultSet();
    }
}