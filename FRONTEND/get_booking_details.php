<?php
session_start();
header('Content-Type: application/json');

// Check admin session
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin'){
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

include_once '../config/database.php';
include_once '../models/AdminBooking.php';

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();
    $adminBooking = new AdminBooking($db);
    
    $booking = $adminBooking->getBookingById($_GET['id']);
    
    if($booking) {
        echo json_encode(['success' => true, 'booking' => $booking]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid booking ID']);
}
?>