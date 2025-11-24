<?php
$page_title = "Manage News - LET Lab Admin";
include_once 'includes/header.php';

// Check admin session
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin'){
    header("location: login.php");
    exit;
}

include_once 'config/database.php';
include_once 'models/News.php';

$database = new Database();
$db = $database->getConnection();
$news = new News($db);

// Handle form actions
if($_POST){
    if(isset($_POST['add_news'])){
        $news->title = $_POST['title'];
        $news->content = $_POST['content'];
        $news->category = $_POST['category'];
        $news->image_url = $_POST['image_url'];
        $news->status = $_POST['status'];
        $news->publish_date = $_POST['publish_date'];
        
        if($news->create()){
            $_SESSION['message'] = "News article added successfully!";
            header("location: admin_news.php");
            exit;
        }
    }
}

$news_articles = $news->read();
?>

<!-- Admin Navbar (same structure) -->
<!-- ... navbar code ... -->

<div class="admin-container">
    <div class="admin-sidebar">
        <!-- Same sidebar structure -->
        <!-- Change active menu to news -->
        <ul class="sidebar-menu">
            <li class="menu-item"><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i><span>Dashboard</span></a></li>
            <!-- ... other menus ... -->
            <li class="menu-item active"><a href="admin_news.php"><i class="fas fa-newspaper me-2"></i><span>News</span></a></li>
            <!-- ... other menus ... -->
        </ul>
    </div>

    <div class="admin-content">
        <div class="content-header">
            <h1>Manage News</h1>
            <p>Create and manage news articles</p>
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
                    <h5 class="card-title mb-0">News Articles</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                        <i class="fas fa-plus me-1"></i>Add News
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Publish Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($article = $news_articles->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo $article['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($article['title']); ?></strong>
                                    <?php if($article['image_url']): ?>
                                        <br><small class="text-muted"><i class="fas fa-image me-1"></i>Has image</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo htmlspecialchars($article['category']); ?></span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($article['publish_date'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $article['status'] == 'published' ? 'success' : 
                                             ($article['status'] == 'draft' ? 'warning' : 'secondary'); 
                                    ?>">
                                        <?php echo ucfirst($article['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="#" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-eye"></i>
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

<!-- Add News Modal -->
<div class="modal fade" id="addNewsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add News Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="general">General</option>
                                    <option value="research">Research</option>
                                    <option value="events">Events</option>
                                    <option value="achievements">Achievements</option>
                                    <option value="announcements">Announcements</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="publish_date" class="form-label">Publish Date</label>
                                <input type="date" class="form-control" id="publish_date" name="publish_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Featured Image URL</label>
                        <input type="url" class="form-control" id="image_url" name="image_url">
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content *</label>
                        <textarea class="form-control" id="content" name="content" rows="8" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_news" class="btn btn-primary">Publish News</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>