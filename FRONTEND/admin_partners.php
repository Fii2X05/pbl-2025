<?php
$page_title = "Manage Partners - LET Lab Admin";
include_once 'includes/header.php';

// Check admin session
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin'){
    header("location: login.php");
    exit;
}

include_once 'config/database.php';
include_once 'models/Partner.php';

$database = new Database();
$db = $database->getConnection();
$partner = new Partner($db);

// Handle form actions
if($_POST){
    if(isset($_POST['add_partner'])){
        $partner->name = $_POST['name'];
        $partner->description = $_POST['description'];
        $partner->website = $_POST['website'];
        $partner->logo = $_POST['logo'];
        $partner->status = $_POST['status'];
        
        if($partner->create()){
            $_SESSION['message'] = "Partner added successfully!";
            header("location: admin_partners.php");
            exit;
        }
    }
    
    if(isset($_POST['update_partner'])){
        $partner->id = $_POST['id'];
        $partner->name = $_POST['name'];
        $partner->description = $_POST['description'];
        $partner->website = $_POST['website'];
        $partner->logo = $_POST['logo'];
        $partner->status = $_POST['status'];
        
        if($partner->update()){
            $_SESSION['message'] = "Partner updated successfully!";
            header("location: admin_partners.php");
            exit;
        }
    }
}

if(isset($_GET['delete_id'])){
    $partner->id = $_GET['delete_id'];
    if($partner->delete()){
        $_SESSION['message'] = "Partner deleted successfully!";
        header("location: admin_partners.php");
        exit;
    }
}

$partners = $partner->read();
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
            <h5>Navigation</h5>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="admin_dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-item active">
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
            <li class="menu-item">
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
                    <span>Absent</span>
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
            <h1>Manage Partners</h1>
            <p>Manage your organization partners and collaborators</p>
        </div>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Partners List</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
                        <i class="fas fa-plus me-1"></i>Add Partner
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Website</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $partners->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td>
                                    <?php if($row['logo']): ?>
                                        <img src="<?php echo $row['logo']; ?>" alt="Logo" class="partner-logo">
                                    <?php else: ?>
                                        <div class="no-logo">No Logo</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                                    <?php if($row['description']): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($row['description']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($row['website']): ?>
                                        <a href="<?php echo $row['website']; ?>" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-external-link-alt me-1"></i>Visit
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $row['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editPartnerModal"
                                                data-id="<?php echo $row['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                                data-description="<?php echo htmlspecialchars($row['description']); ?>"
                                                data-website="<?php echo $row['website']; ?>"
                                                data-logo="<?php echo $row['logo']; ?>"
                                                data-status="<?php echo $row['status']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="admin_partners.php?delete_id=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to delete this partner?')">
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
</div>

<!-- Add Partner Modal -->
<div class="modal fade" id="addPartnerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Partner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Partner Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="website" class="form-label">Website URL</label>
                        <input type="url" class="form-control" id="website" name="website">
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo URL</label>
                        <input type="url" class="form-control" id="logo" name="logo">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_partner" class="btn btn-primary">Add Partner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Partner Modal -->
<div class="modal fade" id="editPartnerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Partner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Partner Name *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_website" class="form-label">Website URL</label>
                        <input type="url" class="form-control" id="edit_website" name="website">
                    </div>
                    <div class="mb-3">
                        <label for="edit_logo" class="form-label">Logo URL</label>
                        <input type="url" class="form-control" id="edit_logo" name="logo">
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_partner" class="btn btn-primary">Update Partner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Edit modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editPartnerModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const description = button.getAttribute('data-description');
        const website = button.getAttribute('data-website');
        const logo = button.getAttribute('data-logo');
        const status = button.getAttribute('data-status');
        
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_website').value = website;
        document.getElementById('edit_logo').value = logo;
        document.getElementById('edit_status').value = status;
    });
});
</script>

<style>
.partner-logo {
    width: 50px;
    height: 50px;
    object-fit: contain;
    border-radius: 5px;
    border: 1px solid #dee2e6;
}

.no-logo {
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    border: 1px dashed #dee2e6;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    color: #6c757d;
}
</style>

<?php include_once 'includes/footer.php'; ?>