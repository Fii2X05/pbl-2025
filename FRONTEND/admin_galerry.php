<?php
$page_title = "Gallery Management - LET Lab Admin";
include_once 'includes/header.php';

// Check admin session
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin'){
    header("location: login.php");
    exit;
}

include_once 'config/database.php';
include_once 'models/Gallery.php';

$database = new Database();
$db = $database->getConnection();
$gallery = new Gallery($db);

// Handle form actions
if($_POST){
    if(isset($_POST['add_gallery'])){
        $gallery->title = $_POST['title'];
        $gallery->description = $_POST['description'];
        $gallery->image_url = $_POST['image_url'];
        $gallery->category = $_POST['category'];
        $gallery->status = $_POST['status'];
        
        if($gallery->create()){
            $_SESSION['message'] = "Gallery item added successfully!";
            header("location: admin_gallery.php");
            exit;
        }
    }
    
    if(isset($_POST['update_gallery'])){
        $gallery->id = $_POST['id'];
        $gallery->title = $_POST['title'];
        $gallery->description = $_POST['description'];
        $gallery->image_url = $_POST['image_url'];
        $gallery->category = $_POST['category'];
        $gallery->status = $_POST['status'];
        
        if($gallery->update()){
            $_SESSION['message'] = "Gallery item updated successfully!";
            header("location: admin_gallery.php");
            exit;
        }
    }
}

if(isset($_GET['delete_id'])){
    $gallery->id = $_GET['delete_id'];
    if($gallery->delete()){
        $_SESSION['message'] = "Gallery item deleted successfully!";
        header("location: admin_gallery.php");
        exit;
    }
}

$gallery_items = $gallery->read();
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
            <li class="menu-item active">
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
                    <span>Absensi</span>
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
            <h1>Gallery Management</h1>
            <p>Kelola Foto & Documentasi InLET</p>
        </div>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Filter and Search Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="filter-section">
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="events">Events</option>
                        <option value="research">Research</option>
                        <option value="facilities">Facilities</option>
                        <option value="team">Team</option>
                        <option value="products">Products</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="search-section">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search gallery items...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Gallery Items</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGalleryModal">
                        <i class="fas fa-plus me-1"></i>Add Gallery Item
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="gallery-grid">
                    <?php while($item = $gallery_items->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="gallery-item" data-category="<?php echo $item['category']; ?>">
                        <div class="gallery-card">
                            <div class="gallery-image">
                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                <div class="gallery-overlay">
                                    <div class="gallery-actions">
                                        <button class="btn btn-sm btn-light me-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewGalleryModal"
                                                data-image="<?php echo $item['image_url']; ?>"
                                                data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                                data-description="<?php echo htmlspecialchars($item['description']); ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-primary me-1"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editGalleryModal"
                                                data-id="<?php echo $item['id']; ?>"
                                                data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                                data-description="<?php echo htmlspecialchars($item['description']); ?>"
                                                data-image="<?php echo $item['image_url']; ?>"
                                                data-category="<?php echo $item['category']; ?>"
                                                data-status="<?php echo $item['status']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="admin_gallery.php?delete_id=<?php echo $item['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this gallery item?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="gallery-info">
                                <h6 class="gallery-title"><?php echo htmlspecialchars($item['title']); ?></h6>
                                <p class="gallery-description"><?php echo htmlspecialchars(substr($item['description'], 0, 100)); ?>...</p>
                                <div class="gallery-meta">
                                    <span class="badge bg-info"><?php echo ucfirst($item['category']); ?></span>
                                    <span class="badge bg-<?php echo $item['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($item['status']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Gallery Modal -->
<div class="modal fade" id="addGalleryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Gallery Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="events">Events</option>
                                    <option value="research">Research</option>
                                    <option value="facilities">Facilities</option>
                                    <option value="team">Team</option>
                                    <option value="products">Products</option>
                                    <option value="documentation">Documentation</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image_url" class="form-label">Image URL *</label>
                                <input type="url" class="form-control" id="image_url" name="image_url" required>
                                <div class="form-text">Enter the URL of the image</div>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter description for this gallery item..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_gallery" class="btn btn-primary">Add to Gallery</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Gallery Modal -->
<div class="modal fade" id="editGalleryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Gallery Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Title *</label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_category" class="form-label">Category *</label>
                                <select class="form-control" id="edit_category" name="category" required>
                                    <option value="events">Events</option>
                                    <option value="research">Research</option>
                                    <option value="facilities">Facilities</option>
                                    <option value="team">Team</option>
                                    <option value="products">Products</option>
                                    <option value="documentation">Documentation</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_image_url" class="form-label">Image URL *</label>
                                <input type="url" class="form-control" id="edit_image_url" name="image_url" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_gallery" class="btn btn-primary">Update Gallery Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Gallery Modal -->
<div class="modal fade" id="viewGalleryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTitle">Gallery Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" alt="" id="viewImage" class="img-fluid mb-3" style="max-height: 400px; object-fit: contain;">
                <h4 id="viewItemTitle"></h4>
                <p id="viewDescription" class="text-muted"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit modal functionality
    const editModal = document.getElementById('editGalleryModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        document.getElementById('edit_id').value = button.getAttribute('data-id');
        document.getElementById('edit_title').value = button.getAttribute('data-title');
        document.getElementById('edit_description').value = button.getAttribute('data-description');
        document.getElementById('edit_image_url').value = button.getAttribute('data-image');
        document.getElementById('edit_category').value = button.getAttribute('data-category');
        document.getElementById('edit_status').value = button.getAttribute('data-status');
    });

    // View modal functionality
    const viewModal = document.getElementById('viewGalleryModal');
    viewModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        document.getElementById('viewImage').src = button.getAttribute('data-image');
        document.getElementById('viewItemTitle').textContent = button.getAttribute('data-title');
        document.getElementById('viewDescription').textContent = button.getAttribute('data-description');
    });

    // Category filter functionality
    const categoryFilter = document.getElementById('categoryFilter');
    categoryFilter.addEventListener('change', function() {
        const selectedCategory = this.value;
        const galleryItems = document.querySelectorAll('.gallery-item');
        
        galleryItems.forEach(item => {
            if (selectedCategory === '' || item.getAttribute('data-category') === selectedCategory) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>

<style>
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.gallery-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
}

.gallery-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.gallery-image {
    position: relative;
    overflow: hidden;
    height: 200px;
}

.gallery-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-card:hover .gallery-image img {
    transform: scale(1.05);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-card:hover .gallery-overlay {
    opacity: 1;
}

.gallery-actions {
    display: flex;
    gap: 0.5rem;
}

.gallery-info {
    padding: 1rem;
}

.gallery-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-size: 0.95rem;
}

.gallery-description {
    color: #6c757d;
    font-size: 0.85rem;
    line-height: 1.4;
    margin-bottom: 0.75rem;
}

.gallery-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-section .form-select,
.search-section .form-control {
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.search-section .input-group {
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .gallery-image {
        height: 180px;
    }
}

@media (max-width: 576px) {
    .gallery-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include_once 'includes/footer.php'; ?>