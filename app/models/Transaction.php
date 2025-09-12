<?php

namespace App\Models;

use App\Core\Database;

class Transaction
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Finds a transaction by its ID. In a real app, this would be more robust.
     * For the demo, we'll just check if it exists.
     * @param int $id
     * @return mixed
     */
    public function find($id)
    {
        $this->db->query("SELECT * FROM transactions WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create($data)
    {
        $this->db->query("INSERT INTO transactions (package_id, amount, payment_status, order_id) VALUES (:package_id, :amount, :payment_status, :order_id)");
        $this->db->bind(':package_id', $data['package_id']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':payment_status', $data['payment_status']);
        $this->db->bind(':order_id', $data['order_id']);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function updatePaymentInfo($id, $token, $url)
    {
        $this->db->query("UPDATE transactions SET payment_token = :token, payment_url = :url WHERE id = :id");
        $this->db->bind(':token', $token);
        $this->db->bind(':url', $url);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function findByOrderId($order_id)
    {
        $this->db->query("SELECT * FROM transactions WHERE order_id = :order_id");
        $this->db->bind(':order_id', $order_id);
        return $this->db->single();
    }

    public function updateStatusByOrderId($order_id, $status)
    {
        $this->db->query("UPDATE transactions SET payment_status = :status WHERE order_id = :order_id");
        $this->db->bind(':status', $status);
        $this->db->bind(':order_id', $order_id);
        return $this->db->execute();
    }

    public function updateStatus($id, $status)
    {
        $this->db->query("UPDATE transactions SET payment_status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getSummary()
    {
        $summary = [];

        // Total Revenue (hanya dari transaksi sukses)
        $this->db->query("SELECT SUM(amount) as total_revenue FROM transactions WHERE payment_status = 'success'");
        $summary['total_revenue'] = $this->db->single()->total_revenue ?? 0;

        // Total Transactions (hanya yang sukses)
        $this->db->query("SELECT COUNT(id) as total_transactions FROM transactions WHERE payment_status = 'success'");
        $summary['total_transactions'] = $this->db->single()->total_transactions ?? 0;

        // Revenue Today
        $this->db->query("SELECT SUM(amount) as revenue_today FROM transactions WHERE payment_status = 'success' AND DATE(created_at) = CURDATE()");
        $summary['revenue_today'] = $this->db->single()->revenue_today ?? 0;

        // Transactions Today
        $this->db->query("SELECT COUNT(id) as transactions_today FROM transactions WHERE payment_status = 'success' AND DATE(created_at) = CURDATE()");
        $summary['transactions_today'] = $this->db->single()->transactions_today ?? 0;

        return (object) $summary;
    }

    public function getAll()
    {
        $this->db->query("
            SELECT t.*, p.name as package_name 
            FROM transactions t
            LEFT JOIN packages p ON t.package_id = p.id
            ORDER BY t.created_at DESC
        ");
        return $this->db->resultSet();
    }

    public function getRevenueByDate($date)
    {
        $this->db->query("SELECT SUM(amount) as revenue FROM transactions WHERE payment_status = 'success' AND DATE(created_at) = :date");
        $this->db->bind(':date', $date);
        $result = $this->db->single();
        return $result ? $result->revenue : 0;
    }

    public function getRevenueTrends($days = 30)
    {
        $this->db->query("
            SELECT 
                DATE(created_at) as date,
                SUM(CASE WHEN payment_status = 'success' THEN amount ELSE 0 END) as revenue,
                COUNT(CASE WHEN payment_status = 'success' THEN 1 END) as successful_transactions,
                COUNT(*) as total_transactions
            FROM transactions 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
            GROUP BY DATE(created_at)
            ORDER BY date DESC
        ");
        $this->db->bind(':days', $days);
        return $this->db->resultSet();
    }
}