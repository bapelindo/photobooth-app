<?php

namespace App\Models;

use App\Core\Database;

class PhotoSession
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create($data)
    {
        $this->db->query("INSERT INTO photo_sessions (transaction_id, selected_frames, session_status, session_start_time) VALUES (:transaction_id, :selected_frames, :session_status, :session_start_time)");
        $this->db->bind(':transaction_id', $data['transaction_id']);
        $this->db->bind(':selected_frames', $data['selected_frames']);
        $this->db->bind(':session_status', $data['session_status'] ?? 'started');
        $this->db->bind(':session_start_time', date('Y-m-d H:i:s'));
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function find($id)
    {
        $this->db->query("SELECT * FROM photo_sessions WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function findByTransactionId($transaction_id)
    {
        $this->db->query("SELECT * FROM photo_sessions WHERE transaction_id = :transaction_id ORDER BY id DESC LIMIT 1");
        $this->db->bind(':transaction_id', $transaction_id);
        return $this->db->single();
    }

    public function updateStatus($id, $status)
    {
        $this->db->query("UPDATE photo_sessions SET session_status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updatePhotoCount($id, $photos_taken, $photos_saved)
    {
        $this->db->query("UPDATE photo_sessions SET photos_taken = :photos_taken, photos_saved = :photos_saved WHERE id = :id");
        $this->db->bind(':photos_taken', $photos_taken);
        $this->db->bind(':photos_saved', $photos_saved);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function completeSession($id)
    {
        $this->db->query("UPDATE photo_sessions SET session_status = 'completed', session_end_time = :end_time WHERE id = :id");
        $this->db->bind(':end_time', date('Y-m-d H:i:s'));
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getSessionPhotos($session_id)
    {
        $this->db->query("SELECT * FROM photo_session_photos WHERE session_id = :session_id ORDER BY taken_at ASC");
        $this->db->bind(':session_id', $session_id);
        return $this->db->resultSet();
    }

    public function getSavedPhotos($session_id)
    {
        $this->db->query("SELECT * FROM photo_session_photos WHERE session_id = :session_id AND is_saved = 1 ORDER BY taken_at ASC");
        $this->db->bind(':session_id', $session_id);
        return $this->db->resultSet();
    }

    public function getAllWithDetails()
    {
        $this->db->query("
            SELECT ps.*, t.order_id, p.name as package_name, p.price, 
                   COUNT(psp.id) as photos_count,
                   COUNT(ph.id) as photostrips_count
            FROM photo_sessions ps
            JOIN transactions t ON ps.transaction_id = t.id
            JOIN packages p ON t.package_id = p.id
            LEFT JOIN photo_session_photos psp ON ps.id = psp.session_id AND psp.is_saved = 1
            LEFT JOIN photostrips ph ON ps.id = ph.session_id
            GROUP BY ps.id
            ORDER BY ps.created_at DESC
        ");
        return $this->db->resultSet();
    }

    public function getSessionWithDetails($session_id)
    {
        $this->db->query("
            SELECT ps.*, t.order_id, t.amount, p.name as package_name, 
                   p.price, p.frame_limit, p.session_duration, p.max_save_photos
            FROM photo_sessions ps
            JOIN transactions t ON ps.transaction_id = t.id
            JOIN packages p ON t.package_id = p.id
            WHERE ps.id = :session_id
        ");
        $this->db->bind(':session_id', $session_id);
        return $this->db->single();
    }

    public function getSessionStatistics()
    {
        $this->db->query("
            SELECT 
                COUNT(*) as total_sessions,
                COUNT(CASE WHEN session_status = 'completed' THEN 1 END) as completed_sessions,
                COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as sessions_today,
                AVG(photos_saved) as avg_photos_per_session
            FROM photo_sessions
        ");
        return $this->db->single();
    }

    public function getRecentSessions($limit = 10)
    {
        $this->db->query("
            SELECT ps.*, t.order_id, p.name as package_name,
                   ps.photos_saved, ps.session_status
            FROM photo_sessions ps
            JOIN transactions t ON ps.transaction_id = t.id
            JOIN packages p ON t.package_id = p.id
            ORDER BY ps.created_at DESC
            LIMIT :limit
        ");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getSessionCountByDate($date)
    {
        $this->db->query("SELECT COUNT(*) as count FROM photo_sessions WHERE DATE(created_at) = :date");
        $this->db->bind(':date', $date);
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    public function getPhotosTakenByDate($date)
    {
        $this->db->query("
            SELECT SUM(ps.photos_taken) as total 
            FROM photo_sessions ps 
            WHERE DATE(ps.created_at) = :date
        ");
        $this->db->bind(':date', $date);
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }

    public function getAverageSessionDuration()
    {
        $this->db->query("
            SELECT AVG(TIMESTAMPDIFF(SECOND, session_start_time, session_end_time)) as avg_duration
            FROM photo_sessions 
            WHERE session_status = 'completed' AND session_end_time IS NOT NULL
        ");
        $result = $this->db->single();
        return $result ? round($result->avg_duration) : 0;
    }

    public function getSessionAnalytics()
    {
        $this->db->query("
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as session_count,
                AVG(photos_taken) as avg_photos_taken,
                AVG(photos_saved) as avg_photos_saved
            FROM photo_sessions 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date DESC
        ");
        return $this->db->resultSet();
    }

    public function delete($session_id)
    {
        $this->db->query("DELETE FROM photo_sessions WHERE id = :id");
        $this->db->bind(':id', $session_id);
        return $this->db->execute();
    }
}