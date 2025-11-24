<?php
class Booking {
    private $conn;
    private $table_name = "bookings";

    public $id;
    public $user_id;
    public $booking_type;
    public $item_name;
    public $start_date;
    public $end_date;
    public $status;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getActiveBookings() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status = 'active'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getUpcomingBookings($limit = 5) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE start_date >= CURDATE() AND status = 'active'
                  ORDER BY start_date ASC 
                  LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    // Tambahkan method-method ini di class Booking

public function getTotalBookings() {
    $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

public function getPendingBookings() {
    $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status = 'pending'";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

public function getConfirmedBookings() {
    $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status = 'confirmed'";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

public function getTodayBookings() {
    $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE DATE(created_at) = CURDATE()";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

public function getAllBookings() {
    $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}
}
?>