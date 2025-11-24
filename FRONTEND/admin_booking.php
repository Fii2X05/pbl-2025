<?php
$page_title = "Booking Management - LET Lab Admin";
include_once 'includes/header.php';

// Check admin session
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin'){
    header("location: login.php");
    exit;
}

include_once 'config/database.php';
include_once 'models/Booking.php'; // Gunakan class Booking biasa

$database = new Database();
$db = $database->getConnection();
$booking = new Booking($db);

// Handle status update
if($_POST && isset($_POST['update_status'])){
    $booking->id = $_POST['booking_id'];
    $booking->status = $_POST['status'];
    
    if($booking->updateStatus()){
        $_SESSION['message'] = "Booking status updated successfully!";
        header("location: admin_booking.php");
        exit;
    } else {
        $_SESSION['message'] = "Error updating booking status!";
    }
}

// Handle delete booking
if(isset($_GET['delete_id'])){
    $booking->id = $_GET['delete_id'];
    if($booking->delete()){
        $_SESSION['message'] = "Booking deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting booking!";
    }
    header("location: admin_booking.php");
    exit;
}

// Get booking statistics
$totalBookings = $booking->getTotalBookings();
$pendingBookings = $booking->getBookingsCountByStatus('pending');
$approvedBookings = $booking->getBookingsCountByStatus('approved');
$completedBookings = $booking->getBookingsCountByStatus('completed');
$cancelledBookings = $booking->getBookingsCountByStatus('cancelled');

// Get all bookings
$bookings = $booking->getAllBookings();

// Equipment data (bisa diganti dengan data dari database)
$equipment = [
    'Projector' => ['total' => 6, 'available' => 5, 'in_use' => 1],
    'Camera' => ['total' => 2, 'available' => 2, 'in_use' => 0],
    'Speaker' => ['total' => 6, 'available' => 6, 'in_use' => 0],
    'Laptop' => ['total' => 4, 'available' => 3, 'in_use' => 1],
    'Microphone' => ['total' => 4, 'available' => 4, 'in_use' => 0],
    'White board' => ['total' => 3, 'available' => 3, 'in_use' => 0]
];

// Get room availability
$isRoomAvailable = $booking->getRoomAvailabilityStatus();
?>

<!-- Admin Navbar -->
<nav class="navbar navbar-expand-lg navbar-admin sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">
            <div class="admin-logo">
                <i class="fas fa-crown me-2"></i>
                <span>Admin Panel</span>
            </div>
        </a>
        
        <div class="navbar-actions ms-auto">
            <div class="admin-info me-3">
                <i class="fas fa-user-shield me-1"></i>
                <span class="admin-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <small class="badge bg-warning">Admin</small>
            </div>
            <a href="logout.php" class="btn btn-logout">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
        </div>
    </div>
</nav>

<div class="admin-container">
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <h5>INFORMATION AND LEARNING</h5>
            <h6>MANUFACTURED TECHNOLOGY</h6>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="admin_dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-item active">
                <a href="admin_booking.php">
                    <i class="fas fa-calendar-check me-2"></i>
                    <span>Booking</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="admin_ruangan.php">
                    <i class="fas fa-building me-2"></i>
                    <span>Room Management</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="admin_alat.php">
                    <i class="fas fa-laptop me-2"></i>
                    <span>Equipment Management</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="admin_users.php">
                    <i class="fas fa-users me-2"></i>
                    <span>User Management</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="admin-content">
        <div class="content-header">
            <h1>Booking Management</h1>
            <p>Kelola semua booking ruangan dan peralatan</p>
        </div>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Booking Statistics -->
        <div class="row mb-4">
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card booking-stats">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $totalBookings; ?></h3>
                        <p>Total Booking</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card booking-stats">
                    <div class="stats-icon" style="background: #ffc107;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $pendingBookings; ?></h3>
                        <p>Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card booking-stats">
                    <div class="stats-icon" style="background: #28a745;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $approvedBookings; ?></h3>
                        <p>Approved</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card booking-stats">
                    <div class="stats-icon" style="background: #6c757d;">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $completedBookings; ?></h3>
                        <p>Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card booking-stats">
                    <div class="stats-icon" style="background: #dc3545;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $cancelledBookings; ?></h3>
                        <p>Cancelled</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card booking-stats">
                    <div class="stats-icon" style="background: <?php echo $isRoomAvailable ? '#28a745' : '#dc3545'; ?>;">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $isRoomAvailable ? 'Yes' : 'No'; ?></h3>
                        <p>Room Available</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Bookings Table -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">All Bookings</h5>
                            <div class="btn-group">
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-download me-1"></i>Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Item</th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $bookings->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-<?php echo $row['booking_type'] == 'ruangan' ? 'primary' : 'info'; ?>">
                                                <?php echo ucfirst($row['booking_type']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['item_name']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['user_name'] ?? 'User ' . $row['user_id']); ?></td>
                                        <td>
                                            <a href="mailto:<?php echo $row['user_email']; ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($row['user_email']); ?>
                                            </a>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($row['start_date'])); ?></td>
                                        <td>
                                            <?php echo date('H:i', strtotime($row['start_date'])); ?> - 
                                            <?php echo date('H:i', strtotime($row['end_date'])); ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $row['status'] == 'approved' ? 'success' : 
                                                     ($row['status'] == 'pending' ? 'warning' : 
                                                     ($row['status'] == 'completed' ? 'secondary' : 'danger')); 
                                            ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                        <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="approved" <?php echo $row['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
                                                        <option value="completed" <?php echo $row['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                        <option value="cancelled" <?php echo $row['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                    <input type="hidden" name="update_status" value="1">
                                                </form>
                                                <button class="btn btn-sm btn-outline-primary ms-1" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#viewBookingModal"
                                                        data-booking-id="<?php echo $row['id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="admin_booking.php?delete_id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Are you sure you want to delete this booking?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipment Status & Room Availability -->
            <div class="col-md-4">
                <!-- Equipment Status Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Kelola Peralatan</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach($equipment as $name => $data): ?>
                        <div class="equipment-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?php echo $name; ?></h6>
                                <span class="badge bg-<?php echo $data['available'] > 0 ? 'success' : 'danger'; ?>">
                                    <?php echo $data['available'] > 0 ? 'Available' : 'Full'; ?>
                                </span>
                            </div>
                            <div class="equipment-stats">
                                <small class="text-muted">
                                    Total Stok: <?php echo $data['total']; ?> | 
                                    Available: <?php echo $data['available']; ?> | 
                                    In Use: <?php echo $data['in_use']; ?>
                                </small>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success" 
                                     style="width: <?php echo ($data['available'] / $data['total']) * 100; ?>%">
                                </div>
                            </div>
                        </div>
                        <?php if($name !== 'White board'): ?>
                        <hr class="my-2">
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Room Status Card -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Status Ruangan Saat ini</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="room-status">
                            <?php if($isRoomAvailable): ?>
                                <i class="fas fa-door-open fa-3x text-success mb-3"></i>
                                <h4 class="text-success">Tersedia / Available</h4>
                                <p class="text-muted">Ruangan siap digunakan</p>
                            <?php else: ?>
                                <i class="fas fa-door-closed fa-3x text-danger mb-3"></i>
                                <h4 class="text-danger">Sedang Digunakan</h4>
                                <p class="text-muted">Ruangan tidak tersedia</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Booking Modal -->
<div class="modal fade" id="viewBookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookingDetails">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Loading booking details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Booking Modal - Load details via AJAX
    const viewModal = document.getElementById('viewBookingModal');
    viewModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const bookingId = button.getAttribute('data-booking-id');
        
        // Load booking details via AJAX
        // Di admin_booking.php - update fetch URL
fetch('get_booking_details.php?id=' + bookingId)
    .then(response => response.json())
    .then(data => {
                if(data.success) {
                    const booking = data.booking;
                    const details = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Booking Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Type:</strong></td>
                                        <td>${booking.booking_type}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Item:</strong></td>
                                        <td>${booking.item_name}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date:</strong></td>
                                        <td>${new Date(booking.start_date).toLocaleDateString()}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Time:</strong></td>
                                        <td>${new Date(booking.start_date).toLocaleTimeString()} - ${new Date(booking.end_date).toLocaleTimeString()}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td><span class="badge bg-${getStatusColor(booking.status)}">${booking.status}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Booked At:</strong></td>
                                        <td>${new Date(booking.created_at).toLocaleString()}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>User Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>${booking.user_name || 'User ' + booking.user_id}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td><a href="mailto:${booking.user_email}">${booking.user_email}</a></td>
                                    </tr>
                                    ${booking.user_phone ? `
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td>${booking.user_phone}</td>
                                    </tr>
                                    ` : ''}
                                </table>
                            </div>
                        </div>
                        ${booking.purpose ? `
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Purpose</h6>
                                <p>${booking.purpose}</p>
                            </div>
                        </div>
                        ` : ''}
                    `;
                    
                    document.getElementById('bookingDetails').innerHTML = details;
                } else {
                    document.getElementById('bookingDetails').innerHTML = `
                        <div class="alert alert-danger">
                            Failed to load booking details.
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('bookingDetails').innerHTML = `
                    <div class="alert alert-danger">
                        Error loading booking details.
                    </div>
                `;
            });
    });
    
    function getStatusColor(status) {
        switch(status) {
            case 'approved': return 'success';
            case 'pending': return 'warning';
            case 'completed': return 'secondary';
            case 'cancelled': return 'danger';
            default: return 'secondary';
        }
    }
});
</script>

<style>
/* CSS styles tetap sama seperti sebelumnya */
.stats-card.booking-stats {
    border-left: 4px solid #3498db;
    padding: 1.2rem;
}

.stats-card.booking-stats .stats-icon {
    width: 45px;
    height: 45px;
    font-size: 1.2rem;
}

.equipment-item {
    padding: 0.5rem 0;
}

.equipment-stats {
    font-size: 0.85rem;
}

.room-status {
    padding: 1.5rem 0;
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #2c3e50;
    border-bottom: 2px solid #dee2e6;
    font-size: 0.9rem;
}

.table td {
    vertical-align: middle;
    font-size: 0.875rem;
}

.form-select-sm {
    width: 120px;
}

.progress {
    background-color: #e9ecef;
    border-radius: 4px;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .stats-card.booking-stats {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .form-select-sm {
        width: 100px;
    }
}
</style>

<?php include_once 'includes/footer.php'; ?>