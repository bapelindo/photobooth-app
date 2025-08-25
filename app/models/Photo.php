<?php

namespace App\Models;

use App\Core\Database;

class Photo
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Create a new photo record in the database.
     * @param array $data with keys 'transaction_id' and 'file_path'
     * @return bool
     */
    public function create($data)
    {
        $this->db->query("INSERT INTO photos (transaction_id, file_path) VALUES (:transaction_id, :file_path)");
        $this->db->bind(':transaction_id', $data['transaction_id']);
        $this->db->bind(':file_path', $data['file_path']);
        return $this->db->execute();
    }

    /**
     * Get all photos from the database.
     * @return array
     */
    public function getAll()
    {
        $this->db->query("SELECT photos.* FROM photos JOIN transactions ON photos.transaction_id = transactions.id ORDER BY photos.created_at DESC");
        return $this->db->resultSet();
    }
}