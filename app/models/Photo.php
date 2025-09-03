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

    public function create($data)
    {
        $this->db->query("INSERT INTO photos (transaction_id, file_path, type) VALUES (:transaction_id, :file_path, :type)");
        $this->db->bind(':transaction_id', $data['transaction_id']);
        $this->db->bind(':file_path', $data['file_path']);
        $this->db->bind(':type', $data['type']);
        return $this->db->execute();
    }
    
    public function getAll()
    {
        $this->db->query("SELECT photos.* FROM photos JOIN transactions ON photos.transaction_id = transactions.id ORDER BY photos.created_at DESC");
        return $this->db->resultSet();
    }

    public function getRawPhotosByTransaction($transaction_id)
    {
        $this->db->query("SELECT * FROM photos WHERE transaction_id = :transaction_id AND type = 'raw' ORDER BY created_at ASC");
        $this->db->bind(':transaction_id', $transaction_id);
        return $this->db->resultSet();
    }
    
    /**
     * FUNGSI BARU: Mengambil semua photostrip final untuk suatu transaksi.
     */
    public function getAllFinalPhotosByTransaction($transaction_id)
    {
        $this->db->query("SELECT * FROM photos WHERE transaction_id = :transaction_id AND type = 'final' ORDER BY created_at ASC");
        $this->db->bind(':transaction_id', $transaction_id);
        return $this->db->resultSet();
    }
    
    public function find($id)
    {
        $this->db->query("SELECT * FROM photos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM photos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateEmailedTo($id, $email)
    {
        $this->db->query("UPDATE photos SET emailed_to = :email WHERE id = :id");
        $this->db->bind(':email', $email);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}