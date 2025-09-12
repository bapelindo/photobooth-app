<?php

namespace App\Models;

use App\Core\Database;

class Photostrip
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create($data)
    {
        $this->db->query("INSERT INTO photostrips (session_id, frame_id, layout_data, decoration_data, final_image_path) VALUES (:session_id, :frame_id, :layout_data, :decoration_data, :final_image_path)");
        $this->db->bind(':session_id', $data['session_id']);
        $this->db->bind(':frame_id', $data['frame_id']);
        $this->db->bind(':layout_data', $data['layout_data'] ?? null);
        $this->db->bind(':decoration_data', $data['decoration_data'] ?? null);
        $this->db->bind(':final_image_path', $data['final_image_path'] ?? null);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function find($id)
    {
        $this->db->query("SELECT * FROM photostrips WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateLayoutData($id, $layout_data)
    {
        $this->db->query("UPDATE photostrips SET layout_data = :layout_data WHERE id = :id");
        $this->db->bind(':layout_data', $layout_data);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateDecorationData($id, $decoration_data)
    {
        $this->db->query("UPDATE photostrips SET decoration_data = :decoration_data WHERE id = :id");
        $this->db->bind(':decoration_data', $decoration_data);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateFinalImage($id, $final_image_path)
    {
        $this->db->query("UPDATE photostrips SET final_image_path = :final_image_path WHERE id = :id");
        $this->db->bind(':final_image_path', $final_image_path);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function markAsPrinted($id)
    {
        $this->db->query("UPDATE photostrips SET is_printed = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getBySessionId($session_id)
    {
        $this->db->query("SELECT ps.*, a.name as frame_name, a.path as frame_path, a.slot_coordinates FROM photostrips ps JOIN assets a ON ps.frame_id = a.id WHERE ps.session_id = :session_id ORDER BY ps.created_at ASC");
        $this->db->bind(':session_id', $session_id);
        return $this->db->resultSet();
    }

    public function delete($id)
    {
        $this->db->query("SELECT final_image_path FROM photostrips WHERE id = :id");
        $this->db->bind(':id', $id);
        $photostrip = $this->db->single();
        
        if ($photostrip && $photostrip->final_image_path) {
            $fullPath = dirname(APPROOT) . '/public' . $photostrip->final_image_path;
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }
        
        $this->db->query("DELETE FROM photostrips WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getAllWithDetails()
    {
        $this->db->query("
            SELECT p.*, a.name as frame_name, a.path as frame_path,
                   ps.transaction_id, ps.session_status, ps.created_at as session_created,
                   t.order_id, pkg.name as package_name,
                   pq.status as print_status, 
                   eq.status as email_status, eq.email as email_address
            FROM photostrips p
            JOIN assets a ON p.frame_id = a.id
            JOIN photo_sessions ps ON p.session_id = ps.id
            JOIN transactions t ON ps.transaction_id = t.id
            JOIN packages pkg ON t.package_id = pkg.id
            LEFT JOIN print_queue pq ON p.id = pq.photostrip_id
            LEFT JOIN email_queue eq ON eq.attachments LIKE CONCAT('%', p.final_image_path, '%')
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ");
        return $this->db->resultSet();
    }

    public function getWithFullDetails($id)
    {
        $this->db->query("
            SELECT p.*, a.name as frame_name, a.path as frame_path, a.slot_coordinates,
                   ps.transaction_id, ps.session_status, ps.photos_taken, ps.photos_saved,
                   t.order_id, t.amount, pkg.name as package_name, pkg.price,
                   COALESCE(pq.status, 'none') as print_status, pq.completed_at as print_completed_at,
                   CASE 
                       WHEN eq.status IS NOT NULL THEN eq.status
                       WHEN ph.emailed_to IS NOT NULL THEN 'sent'
                       ELSE 'none'
                   END as email_status,
                   COALESCE(eq.email, ph.emailed_to, 'not provided') as email_address,
                   eq.completed_at as email_completed_at
            FROM photostrips p
            JOIN assets a ON p.frame_id = a.id
            JOIN photo_sessions ps ON p.session_id = ps.id
            JOIN transactions t ON ps.transaction_id = t.id
            JOIN packages pkg ON t.package_id = pkg.id
            LEFT JOIN print_queue pq ON p.id = pq.photostrip_id
            LEFT JOIN email_queue eq ON (eq.attachments LIKE CONCAT('%', p.final_image_path, '%') 
                                      OR eq.attachments LIKE CONCAT('%session_', ps.id, '%'))
            LEFT JOIN photos ph ON ph.transaction_id = t.id AND ph.emailed_to IS NOT NULL
            WHERE p.id = :id
            GROUP BY p.id
            ORDER BY COALESCE(pq.created_at, '1970-01-01') DESC, COALESCE(eq.created_at, '1970-01-01') DESC
            LIMIT 1
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getTotalCount()
    {
        $this->db->query("SELECT COUNT(*) as count FROM photostrips");
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    public function findBySessionAndFrame($session_id, $frame_id)
    {
        $this->db->query("SELECT * FROM photostrips WHERE session_id = :session_id AND frame_id = :frame_id");
        $this->db->bind(':session_id', $session_id);
        $this->db->bind(':frame_id', $frame_id);
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
        
        $sql = "UPDATE photostrips SET " . implode(', ', $fields) . " WHERE id = :id";
        $this->db->query($sql);
        
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        return $this->db->execute();
    }

}