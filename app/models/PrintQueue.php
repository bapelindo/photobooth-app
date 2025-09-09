<?php

namespace App\Models;

use App\Core\Database;

class PrintQueue
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create($data)
    {
        $this->db->query("INSERT INTO print_queue (photostrip_id, file_path, copies, priority, status, created_at) VALUES (:photostrip_id, :file_path, :copies, :priority, :status, :created_at)");
        $this->db->bind(':photostrip_id', $data['photostrip_id']);
        $this->db->bind(':file_path', $data['file_path']);
        $this->db->bind(':copies', $data['copies'] ?? 1);
        $this->db->bind(':priority', $data['priority'] ?? 1);
        $this->db->bind(':status', $data['status'] ?? 'pending');
        $this->db->bind(':created_at', date('Y-m-d H:i:s'));
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function find($id)
    {
        $this->db->query("SELECT * FROM print_queue WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getPendingJobs($limit = 5)
    {
        $this->db->query("SELECT * FROM print_queue WHERE status = 'pending' ORDER BY priority DESC, created_at ASC LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function updateStatus($id, $status, $error_message = null)
    {
        if ($status === 'completed') {
            $this->db->query("UPDATE print_queue SET status = :status, completed_at = :completed_at, error_message = :error_message WHERE id = :id");
            $this->db->bind(':completed_at', date('Y-m-d H:i:s'));
        } else {
            $this->db->query("UPDATE print_queue SET status = :status, error_message = :error_message WHERE id = :id");
        }
        
        $this->db->bind(':status', $status);
        $this->db->bind(':error_message', $error_message);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function incrementRetries($id)
    {
        $this->db->query("UPDATE print_queue SET retries = retries + 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteOldJobs($days = 7)
    {
        $this->db->query("DELETE FROM print_queue WHERE status = 'completed' AND completed_at < DATE_SUB(NOW(), INTERVAL :days DAY)");
        $this->db->bind(':days', $days);
        return $this->db->execute();
    }

    public function getStats()
    {
        $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed
            FROM print_queue
        ");
        return $this->db->single();
    }
}