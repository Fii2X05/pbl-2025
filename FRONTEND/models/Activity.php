<?php
class Activity {
    private $conn;
    private $table_name = "posts"; 

    public $post_id;
    public $title;
    public $content;       
    public $thumbnail_url; 
    public $author_id;     
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getRecentActivities($limit = 5) {

        $query = "SELECT a.*, u.username 
                  FROM " . $this->table_name . " a 
                  LEFT JOIN users u ON a.author_id = u.user_id 
                  WHERE a.status = 'published' 
                  ORDER BY a.created_at DESC 
                  LIMIT ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function getTotalActivities() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status = 'published'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
?>