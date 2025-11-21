<?php
class Database {
    private $host = "localhost";
    private $port = "5433";
    private $db_name = "postgres";  // Gunakan database default dulu untuk testing
    private $username = "postgres";
    private $password = "12345678";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // DSN PostgreSQL yang benar
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected to PostgreSQL successfully!";
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>