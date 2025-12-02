<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $image_url;
    public $link_demo;
    public $price;
    public $category; // Kolom Baru
    public $status;   // Kolom Baru

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. READ (Ambil data termasuk category & status)
    public function read() {
        $query = "SELECT 
                    product_id as id,
                    name, 
                    description, 
                    image_url, 
                    link_demo, 
                    price,
                    category,
                    status
                  FROM " . $this->table_name . " 
                  ORDER BY product_id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // 2. CREATE
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, description, image_url, link_demo, price, category, status) 
                  VALUES (:name, :desc, :img, :link, :price, :cat, :stat)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->link_demo = htmlspecialchars(strip_tags($this->link_demo));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // Bind
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':desc', $this->description);
        $stmt->bindParam(':img', $this->image_url);
        $stmt->bindParam(':link', $this->link_demo);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':cat', $this->category);
        $stmt->bindParam(':stat', $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 3. UPDATE
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, 
                      description = :desc, 
                      image_url = :img, 
                      link_demo = :link,
                      price = :price,
                      category = :cat,
                      status = :stat
                  WHERE product_id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize & Bind
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->link_demo = htmlspecialchars(strip_tags($this->link_demo));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':desc', $this->description);
        $stmt->bindParam(':img', $this->image_url);
        $stmt->bindParam(':link', $this->link_demo);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':cat', $this->category);
        $stmt->bindParam(':stat', $this->status);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 4. DELETE
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE product_id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute()) { return true; }
        return false;
    }
}
?>