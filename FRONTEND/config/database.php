<?php
class Database {
    private $host = "127.0.0.1";
    private $port = "5433";
    private $db_name = "let_lab";  // Gunakan database default dulu untuk testing
    private $username = "postgres";
    private $password = "deriko15";
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