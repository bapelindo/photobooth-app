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
            INSERT INTO email_queue (recipient_email, recipient_name, subject, body, attachments) 
            VALUES (:email, :name, :subject, :body, :attachments)
        ");
        
        $this->db->bind(':email', $recipientEmail);
        $this->db->bind(':name', $recipientName);
        $this->db->bind(':subject', $subject);
        $this->db->bind(':body', $body);
        $this->db->bind(':attachments', json_encode($attachments));
        
        return $this->db->execute();
    }

    public function getPending($limit = 5)
    {
        $this->db->query("
            SELECT * FROM email_queue 
            WHERE status = 'pending' AND attempts < max_attempts
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
        $this->db->query("UPDATE email_queue SET status = 'sent', processed_at = NOW() WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function markFailed($id, $error = null)
    {
        $this->db->query("
            UPDATE email_queue 
            SET status = 'failed', attempts = attempts + 1, error_message = :error, processed_at = NOW() 
            WHERE id = :id
        ");
        $this->db->bind(':id', $id);
        $this->db->bind(':error', $error);
        return $this->db->execute();
    }

    public function getStats()
    {
        $this->db->query("
            SELECT 
                status,
                COUNT(*) as count
            FROM email_queue 
            GROUP BY status
        ");
        return $this->db->resultSet();
    }
}