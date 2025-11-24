<?php
$page_title = "Activity Management - LET Lab Admin";
include_once 'includes/header.php';

// Check admin session
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin'){
    header("location: login.php");
    exit;
}

include_once 'config/database.php';
include_once 'models/Activity.php';

$database = new Database();
$db = $database->getConnection();
$activity = new Activity($db);

// Handle form actions
if($_POST){
    if(isset($_POST['add_activity'])){
        $activity->title = $_POST['title'];
        $activity->description = $_POST['description'];
        $activity->activity_date = $_POST['activity_date'];
        $activity->location = $_POST['location'];
        $activity->image_url = $_POST['image_url'];
        $activity->status = $_POST['status'];
        $activity->link = $_POST['link'];
        
        if($activity->create()){
            $_SESSION['message'] = "Activity added successfully!";
            header("location: admin_activity.php");
            exit;
        }
    }
    
    if(isset($_POST['update_activity'])){
        $activity->id = $_POST['id'];
        $activity->title = $_POST['title'];
        $activity->description = $_POST['description'];
        $activity->activity_date = $_POST['activity_date'];
        $activity->location = $_POST['location'];
        $activity->image_url = $_POST['image_url'];
        $activity->status = $_POST['status'];
        $activity->link = $_POST['link'];
        
        if($activity->update()){
            $_SESSION['message'] = "Activity updated successfully!";
            header("location: admin_activity.php");
            exit;
        }
    }
}

if(isset($_GET['delete_id'])){
    $activity->id = $_GET['delete_id'];
    if($activity->delete()){
        $_SESSION['message'] = "Activity deleted successfully!";
        header("location: admin_activity.php");
        exit;
    }
}

$activities = $activity->read();
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
            <li class="menu-item">
                <a href="admin_partners.php">
                    <i class="fas fa-handshake me-2"></i>
                    <span>Partners</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="admin_team.php">
                    <i class="fas fa-users me-2"></i>
                    <span>Team</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="admin_products.php">
                    <i class="fas fa-box me-2"></i>
                    <span>Products</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="admin_news.php">
                    <i class="fas fa-newspaper me-2"></i>
                    <span>News</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="admin_gallery.php">
                    <i class="fas fa-images me-2"></i>
                    <span>Gallery</span>
                </a>
            </li>
            <li class="menu-item active">
                <a href="admin_activity.php">
                    <i class="fas fa-chart-line me-2"></i>
                    <span>Activity</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="admin_booking.php">
                    <i class="fas fa-calendar-check me-2"></i>
                    <span>Booking</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="admin_absent.php">
                    <i class="fas fa-clipboard-list me-2"></i>
                    <span>Absents!</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="admin_settings.php">
                    <i class="fas fa-cog me-2"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="admin-content">
        <div class="content-header">
            <h1>Activity Management</h1>
            <p>Kelola Kegiatan InLET</p>
        </div>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stats-info">
                        <h3>88</h3>
                        <p>Total Activities</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="stats-info">
                        <h3>45</h3>
                        <p>Ongoing</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-info">
                        <h3>32</h3>
                        <p>Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-info">
                        <h3>11</h3>
                        <p>Upcoming</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Table -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Activities List</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addActivityModal">
                        <i class="fas fa-plus me-1"></i>Add Activity
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Link</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($item = $activities->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td>
                                    <div class="activity-title">
                                        <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                                        <?php if($item['description']): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars(substr($item['description'], 0, 100)); ?>...</small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="activity-date">
                                        <?php echo date('M j, Y', strtotime($item['activity_date'])); ?>
                                        <?php if($item['activity_date'] == date('Y-m-d')): ?>
                                            <br><small class="badge bg-warning">Today</small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if($item['location']): ?>
                                        <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                                        <?php echo htmlspecialchars($item['location']); ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $item['status'] == 'upcoming' ? 'info' : 
                                             ($item['status'] == 'ongoing' ? 'success' : 
                                             ($item['status'] == 'completed' ? 'secondary' : 'warning')); 
                                    ?>">
                                        <?php echo ucfirst($item['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($item['link']): ?>
                                        <a href="<?php echo $item['link']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt me-1"></i>View
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary me-1"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editActivityModal"
                                                data-id="<?php echo $item['id']; ?>"
                                                data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                                data-description="<?php echo htmlspecialchars($item['description']); ?>"
                                                data-activity_date="<?php echo $item['activity_date']; ?>"
                                                data-location="<?php echo $item['location']; ?>"
                                                data-image_url="<?php echo $item['image_url']; ?>"
                                                data-link="<?php echo $item['link']; ?>"
                                                data-status="<?php echo $item['status']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="admin_activity.php?delete_id=<?php echo $item['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to delete this activity?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            
                            <!-- Sample data from the image -->
                            <tr>
                                <td>
                                    <div class="activity-title">
                                        <strong>Learning Engineering Technology Lab, Week 1, B2</strong>
                                        <br><small class="text-muted">Introduction to engineering technology concepts and lab safety procedures</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="activity-date">
                                        Jan 15, 2024
                                        <br><small class="badge bg-secondary">Completed</small>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                                    Lab B2, Engineering Building
                                </td>
                                <td>
                                    <span class="badge bg-secondary">Completed</span>
                                </td>
                                <td>
                                    <a href="https://pro.auberlationshealthcare-immo/activities/HRM" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt me-1"></i>View
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <div class="activity-title">
                                        <strong>Research Discussion Part 4: Dr. Eng A. All & Rejhjemir</strong>
                                        <br><small class="text-muted">Advanced research methodologies and collaborative projects discussion</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="activity-date">
                                        Feb 2, 2024
                                        <br><small class="badge bg-success">Ongoing</small>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                                    Research Hall, Main Campus
                                </td>
                                <td>
                                    <span class="badge bg-success">Ongoing</span>
                                </td>
                                <td>
                                    <a href="https://pro.auberlationseducationagiosu-investors.org/artguide" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt me-1"></i>View
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <div class="activity-title">
                                        <strong>Learning Engineering Technology Lab, Are you ready for the next challenge?</strong>
                                        <br><small class="text-muted">Hands-on workshop for advanced engineering challenges</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="activity-date">
                                        Mar 10, 2024
                                        <br><small class="badge bg-info">Upcoming</small>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                                    Innovation Lab, Tech Center
                                </td>
                                <td>
                                    <span class="badge bg-info">Upcoming</span>
                                </td>
                                <td>
                                    <a href="https://pro.auberlationsfocusbooken-0733A6B9b7a0c09b" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt me-1"></i>View
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <div class="activity-title">
                                        <strong>Research Discussion Part 5: Any Discussion Plan.</strong>
                                        <br><small class="text-muted">Finalizing research proposals and project timelines</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="activity-date">
                                        Mar 25, 2024
                                        <br><small class="badge bg-info">Upcoming</small>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                                    Conference Room A
                                </td>
                                <td>
                                    <span class="badge bg-info">Upcoming</span>
                                </td>
                                <td>
                                    <a href="https://pro.auberlationsdiversitystrategy.ubcomprivacy" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt me-1"></i>View
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Activity Modal -->
<div class="modal fade" id="addActivityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Activity Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="activity_date" class="form-label">Activity Date *</label>
                                <input type="date" class="form-control" id="activity_date" name="activity_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="upcoming">Upcoming</option>
                                    <option value="ongoing">Ongoing</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe the activity..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image_url" class="form-label">Image URL</label>
                                <input type="url" class="form-control" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="link" class="form-label">Activity Link</label>
                                <input type="url" class="form-control" id="link" name="link" placeholder="https://example.com/activity">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_activity" class="btn btn-primary">Add Activity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Activity Modal -->
<div class="modal fade" id="editActivityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Activity Title *</label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_activity_date" class="form-label">Activity Date *</label>
                                <input type="date" class="form-control" id="edit_activity_date" name="activity_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="edit_location" name="location">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status *</label>
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="upcoming">Upcoming</option>
                                    <option value="ongoing">Ongoing</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="4"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_image_url" class="form-label">Image URL</label>
                                <input type="url" class="form-control" id="edit_image_url" name="image_url">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_link" class="form-label">Activity Link</label>
                                <input type="url" class="form-control" id="edit_link" name="link">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_activity" class="btn btn-primary">Update Activity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit modal functionality
    const editModal = document.getElementById('editActivityModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        document.getElementById('edit_id').value = button.getAttribute('data-id');
        document.getElementById('edit_title').value = button.getAttribute('data-title');
        document.getElementById('edit_description').value = button.getAttribute('data-description');
        document.getElementById('edit_activity_date').value = button.getAttribute('data-activity_date');
        document.getElementById('edit_location').value = button.getAttribute('data-location');
        document.getElementById('edit_image_url').value = button.getAttribute('data-image_url');
        document.getElementById('edit_link').value = button.getAttribute('data-link');
        document.getElementById('edit_status').value = button.getAttribute('data-status');
    });

    // Set default date to today for add modal
    document.getElementById('activity_date').valueAsDate = new Date();
});
</script>

<style>
.stats-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid #3498db;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stats-icon {
    width: 50px;
    height: 50px;
    background: #3498db;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stats-info h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.8rem;
    font-weight: bold;
}

.stats-info p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.activity-title {
    max-width: 300px;
}

.activity-date {
    white-space: nowrap;
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #2c3e50;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.sidebar-header {
    text-align: center;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 1rem;
}

.sidebar-header h5 {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.sidebar-header h6 {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 400;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

.btn-group .btn {
    border-radius: 5px;
}

@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .activity-title {
        max-width: 200px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>

<?php include_once 'includes/footer.php'; ?>