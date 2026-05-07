<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private $host;
    private $user;
    private $pass;
    private $dbname;

    private $dbh; // Database Handler
    private $stmt; // Statement
    private $error;

    public function __construct() {
        $config = include __DIR__ . '/../../config/database.php';

        if (!is_array($config)) {
            die("Error: Database configuration not loaded correctly. Check config/database.php");
        }

        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->pass = $config['password'];
        $this->dbname = $config['dbname'];
        $socket = isset($config['socket']) ? $config['socket'] : null;

        if ($socket) {
            $dsn = 'mysql:unix_socket=' . $socket . ';dbname=' . $this->dbname;
        } else {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        }

        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Database Connection Error: " . $this->error . " | DSN: " . $dsn . " | User: " . $this->user);
        }
    }

    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }

    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}