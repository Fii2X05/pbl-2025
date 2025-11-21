<?php
class Activity {
    private $conn;
    private $table_name = "activities";

    public $id;
    public $user_id;
    public $activity_type;
    public $description;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getRecentActivities($limit = 5) {
        $query = "SELECT a.*, u.username FROM " . $this->table_name . " a 
                  LEFT JOIN users u ON a.user_id = u.id 
                  ORDER BY a.created_at DESC 
                  LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function getTotalActivities() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
?>