<?php

namespace App\Models;

use App\Core\Database;

class PhotoSessionPhoto
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create($data)
    {
        $this->db->query("INSERT INTO photo_session_photos (session_id, file_path, is_saved) VALUES (:session_id, :file_path, :is_saved)");
        $this->db->bind(':session_id', $data['session_id']);
        $this->db->bind(':file_path', $data['file_path']);
        $this->db->bind(':is_saved', $data['is_saved'] ?? 0);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function find($id)
    {
        $this->db->query("SELECT * FROM photo_session_photos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function savePhoto($id)
    {
        $this->db->query("UPDATE photo_session_photos SET is_saved = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function unsavePhoto($id)
    {
        $this->db->query("UPDATE photo_session_photos SET is_saved = 0 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deletePhoto($id)
    {
        $this->db->query("SELECT file_path FROM photo_session_photos WHERE id = :id");
        $this->db->bind(':id', $id);
        $photo = $this->db->single();
        
        if ($photo) {
            $fullPath = dirname(APPROOT) . '/public' . $photo->file_path;
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
            
            $this->db->query("DELETE FROM photo_session_photos WHERE id = :id");
            $this->db->bind(':id', $id);
            return $this->db->execute();
        }
        
        return false;
    }

    public function getBySessionId($session_id)
    {
        $this->db->query("SELECT * FROM photo_session_photos WHERE session_id = :session_id ORDER BY taken_at ASC");
        $this->db->bind(':session_id', $session_id);
        return $this->db->resultSet();
    }
}