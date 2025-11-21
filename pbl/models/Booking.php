<?php
class Booking {
    private $conn;
    private $table_name = "loans"; // Sesuai bd_pbl.sql

    // Properties sesuai kolom tabel loans
    public $loan_id;
    public $borrower_name;
    public $borrower_contact;
    public $borrower_email;
    public $institution;
    public $item_type; // 'tool' atau 'room'
    public $item_id;
    public $qty;
    public $start_time;
    public $end_time;
    public $status;
    public $admin_note;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Menghitung total booking aktif (Untuk Statistik Dashboard)
    public function getActiveBookings() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                  WHERE status IN ('pending', 'approved')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // 2. Mengambil daftar booking lengkap dengan Nama Barang/Ruangan
    // Ini menggunakan teknik JOIN kondisional karena item bisa berupa Tool atau Room
    public function getAllBookings() {
        $query = "SELECT l.*, 
                         CASE 
                            WHEN l.item_type = 'tool' THEN t.name 
                            WHEN l.item_type = 'room' THEN r.name 
                            ELSE 'Unknown Item' 
                         END as item_name
                  FROM " . $this->table_name . " l
                  LEFT JOIN tools t ON l.item_id = t.tool_id AND l.item_type = 'tool'
                  LEFT JOIN rooms r ON l.item_id = r.room_id AND l.item_type = 'room'
                  ORDER BY l.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // 3. Mengambil booking terbaru (Untuk Tabel di Dashboard)
    public function getRecentBookings($limit = 5) {
        $query = "SELECT l.borrower_name, l.created_at, l.status,
                         CASE 
                            WHEN l.item_type = 'tool' THEN t.name 
                            WHEN l.item_type = 'room' THEN r.name 
                            ELSE 'Unknown Item' 
                         END as item_name
                  FROM " . $this->table_name . " l
                  LEFT JOIN tools t ON l.item_id = t.tool_id AND l.item_type = 'tool'
                  LEFT JOIN rooms r ON l.item_id = r.room_id AND l.item_type = 'room'
                  ORDER BY l.created_at DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // 4. Update Status Booking (Approve/Reject/Return)
    public function updateStatus($id, $status, $note = "") {
        $query = "UPDATE " . $this->table_name . " 
                  SET status = :status, admin_note = :note 
                  WHERE loan_id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>