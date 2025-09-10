<?php

namespace App\Models;

use App\Core\Database;

class EmailQueue
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function add($recipientEmail, $recipientName, $subject, $body, $attachments = [])
    {
        $this->db->query("
            INSERT INTO email_queue (email, subject, body, attachments) 
            VALUES (:email, :subject, :body, :attachments)
        ");
        
        $this->db->bind(':email', $recipientEmail);
        $this->db->bind(':subject', $subject);
        $this->db->bind(':body', $body);
        $this->db->bind(':attachments', json_encode($attachments));
        
        return $this->db->execute();
    }

    public function getPending($limit = 5)
    {
        $this->db->query("
            SELECT * FROM email_queue 
            WHERE status = 'pending' AND retries < 3
            ORDER BY created_at ASC 
            LIMIT :limit
        ");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function markProcessing($id)
    {
        $this->db->query("UPDATE email_queue SET status = 'processing' WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function markSent($id)
    {
        $this->db->query("UPDATE email_queue SET status = 'completed', completed_at = NOW() WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function markFailed($id, $error = null)
    {
        $this->db->query("
            UPDATE email_queue 
            SET status = 'failed', retries = retries + 1, error_message = :error, completed_at = NOW() 
            WHERE id = :id
        ");
        $this->db->bind(':id', $id);
        $this->db->bind(':error', $error);
        return $this->db->execute();
    }

    public function getPendingJobs($limit = 20)
    {
        $this->db->query("
            SELECT * FROM email_queue 
            WHERE status IN ('pending', 'processing', 'failed') 
            ORDER BY created_at DESC 
            LIMIT :limit
        ");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function find($id)
    {
        $this->db->query("SELECT * FROM email_queue WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function resetJob($id)
    {
        $this->db->query("UPDATE email_queue SET status = 'pending', retries = 0, error_message = NULL WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM email_queue WHERE id = :id");
        $this->db->bind(':id', $id);
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
            FROM email_queue
        ");
        return $this->db->single();
    }
}