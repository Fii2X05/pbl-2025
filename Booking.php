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
}
?>