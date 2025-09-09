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

    public function findOldestUnsavedBySession($session_id)
    {
        $this->db->query("SELECT * FROM photo_session_photos WHERE session_id = :session_id AND is_saved = 0 ORDER BY taken_at ASC LIMIT 1");
        $this->db->bind(':session_id', $session_id);
        return $this->db->single();
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        
        $sql = "UPDATE photo_session_photos SET " . implode(', ', $fields) . " WHERE id = :id";
        $this->db->query($sql);
        
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        return $this->db->execute();
    }

    public function removeDuplicatePhotos($session_id)
    {
        try {
            // Find duplicate photos based on file_path within the same session
            $this->db->query("
                SELECT file_path, MIN(id) as keep_id, GROUP_CONCAT(id) as all_ids, COUNT(*) as count
                FROM photo_session_photos 
                WHERE session_id = :session_id 
                GROUP BY file_path, session_id 
                HAVING COUNT(*) > 1
            ");
            $this->db->bind(':session_id', $session_id);
            $duplicates = $this->db->resultSet();
            
            $deletedCount = 0;
            foreach ($duplicates as $duplicate) {
                // Get all IDs except the one to keep
                $allIds = explode(',', $duplicate->all_ids);
                $idsToDelete = array_filter($allIds, function($id) use ($duplicate) {
                    return $id != $duplicate->keep_id;
                });
                
                if (!empty($idsToDelete)) {
                    // Delete duplicates one by one to avoid binding issues
                    foreach ($idsToDelete as $id) {
                        $this->db->query("DELETE FROM photo_session_photos WHERE id = :id");
                        $this->db->bind(':id', $id);
                        $this->db->execute();
                        $deletedCount++;
                    }
                }
            }
            
            return $deletedCount;
            
        } catch (Exception $e) {
            error_log('Error removing duplicate photos: ' . $e->getMessage());
            return false;
        }
    }

    public function clearSessionPhotos($session_id)
    {
        try {
            // First get all photos for this session to delete files
            $this->db->query("SELECT file_path FROM photo_session_photos WHERE session_id = :session_id");
            $this->db->bind(':session_id', $session_id);
            $photos = $this->db->resultSet();
            
            // Delete physical files
            $deletedFiles = 0;
            foreach ($photos as $photo) {
                $fullPath = dirname(APPROOT) . '/public' . $photo->file_path;
                if (file_exists($fullPath)) {
                    if (@unlink($fullPath)) {
                        $deletedFiles++;
                    }
                }
            }
            
            // Delete database records
            $this->db->query("DELETE FROM photo_session_photos WHERE session_id = :session_id");
            $this->db->bind(':session_id', $session_id);
            $success = $this->db->execute();
            
            return [
                'success' => $success,
                'deleted_records' => count($photos),
                'deleted_files' => $deletedFiles
            ];
            
        } catch (Exception $e) {
            error_log('Error clearing session photos: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'deleted_records' => 0,
                'deleted_files' => 0
            ];
        }
    }
}