<?php

namespace App\Models;

use App\Core\Database;

class Admin
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findByUsername($username)
    {
        $this->db->query("SELECT * FROM admins WHERE username = :username");
        $this->db->bind(':username', $username);
        $result = $this->db->single();
        return $result;
    }
}