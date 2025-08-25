<?php
namespace App\Models;

use App\Core\Database;

class Event {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getActiveEvent() {
        $this->db->query("SELECT * FROM events WHERE id = :id AND status = 'active'");
        $this->db->bind(':id', ACTIVE_EVENT_ID);
        return $this->db->single();
    }
    
    public function findById($id) {
        $this->db->query("SELECT * FROM events WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    // Tambahkan method-method ini
    public function getAllEvents() {
        $this->db->query('SELECT * FROM events ORDER BY event_date DESC');
        return $this->db->resultSet();
    }

    public function addEvent($data) {
        $this->db->query('INSERT INTO events (event_name, event_date, qris_image_path, status) VALUES (:event_name, :event_date, :qris_image_path, :status)');
        $this->db->bind(':event_name', $data['event_name']);
        $this->db->bind(':event_date', $data['event_date']);
        $this->db->bind(':qris_image_path', $data['qris_image_path']);
        $this->db->bind(':status', $data['status']);
        return $this->db->execute();
    }
// ... method lainnya untuk update dan delete bisa ditambahkan serupa
}