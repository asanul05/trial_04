<?php
/**
 * OSCA API Configuration & Database Connection
 * File: api/config/database.php
 */
require_once '../helpers/response.php';

class Database {
    private $host = "localhost";
    private $db_name = "osca_system";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            Response::error("Database connection error: " . $exception->getMessage(), 500);
            exit();
        }
        
        return $this->conn;
    }
}