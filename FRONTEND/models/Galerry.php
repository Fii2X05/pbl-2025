<?php
class Gallery {
    private $conn;
    private $table_name = "gallery";

    public $id;
    public $title;
    public $description;
    public $image_url;
    public $category;
    public $status;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new gallery item
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET title=:title, description=:description, image_url=:image_url, 
                      category=:category, status=:status";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize input
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        // Bind parameters
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":status", $this->status);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all gallery items
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update gallery item
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title=:title, description=:description, image_url=:image_url, 
                      category=:category, status=:status 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize input
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind parameters
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete gallery item
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get gallery items by category
    public function readByCategory($category) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE category = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category);
        $stmt->execute();
        return $stmt;
    }

    // Get total gallery items count
    public function getTotalGalleryItems() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
?>