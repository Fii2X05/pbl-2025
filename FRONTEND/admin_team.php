<?php
$page_title = "Manage Team - LET Lab Admin";
include_once 'includes/header.php';

// Check admin session
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin'){
    header("location: login.php");
    exit;
}

include_once 'config/database.php';
include_once 'models/Team.php';

$database = new Database();
$db = $database->getConnection();
$team = new Team($db);

// Handle form actions
if($_POST){
    if(isset($_POST['add_member'])){
        $team->name = $_POST['name'];
        $team->position = $_POST['position'];
        $team->email = $_POST['email'];
        $team->phone = $_POST['phone'];
        $team->bio = $_POST['bio'];
        $team->photo = $_POST['photo'];
        $team->status = $_POST['status'];
        
        if($team->create()){
            $_SESSION['message'] = "Team member added successfully!";
            header("location: admin_team.php");
            exit;
        }
    }
}

$team_members = $team->read();
?>

<!-- Admin Navbar (same structure as dashboard) -->
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
        <!-- Same sidebar structure as dashboard -->
        <!-- Change active menu to team -->
        <ul class="sidebar-menu">
            <li class="menu-item"><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i><span>Dashboard</span></a></li>
            <li class="menu-item"><a href="admin_partners.php"><i class="fas fa-handshake me-2"></i><span>Partners</span></a></li>
            <li class="menu-item active"><a href="admin_team.php"><i class="fas fa-users me-2"></i><span>Team</span></a></li>
            <!-- ... other menu items ... -->
        </ul>
    </div>

    <div class="admin-content">
        <div class="content-header">
            <h1>Manage Team</h1>
            <p>Manage your team members and their information</p>
        </div>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php while($member = $team_members->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-4 mb-4">
                <div class="card team-card">
                    <div class="card-body text-center">
                        <div class="team-photo mb-3">
                            <?php if($member['photo']): ?>
                                <img src="<?php echo $member['photo']; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" class="rounded-circle">
                            <?php else: ?>
                                <div class="no-photo rounded-circle">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h5 class="card-title"><?php echo htmlspecialchars($member['name']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars($member['position']); ?></p>
                        <?php if($member['email']): ?>
                            <p class="card-text">
                                <i class="fas fa-envelope me-2"></i>
                                <small><?php echo htmlspecialchars($member['email']); ?></small>
                            </p>
                        <?php endif; ?>
                        <?php if($member['bio']): ?>
                            <p class="card-text">
                                <small><?php echo htmlspecialchars(substr($member['bio'], 0, 100)); ?>...</small>
                            </p>
                        <?php endif; ?>
                        <div class="team-actions mt-3">
                            <span class="badge bg-<?php echo $member['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                <?php echo ucfirst($member['status']); ?>
                            </span>
                            <div class="btn-group mt-2">
                                <button class="btn btn-sm btn-outline-primary">Edit</button>
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="text-center mt-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                <i class="fas fa-plus me-1"></i>Add Team Member
            </button>
        </div>
    </div>
</div>

<!-- Add Team Modal -->
<div class="modal fade" id="addTeamModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Team Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="position" class="form-label">Position *</label>
                                <input type="text" class="form-control" id="position" name="position" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo URL</label>
                                <input type="url" class="form-control" id="photo" name="photo">
                            </div>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4"></textarea>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_member" class="btn btn-primary">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.team-card {
    transition: transform 0.2s;
}

.team-card:hover {
    transform: translateY(-5px);
}

.team-photo img,
.team-photo .no-photo {
    width: 100px;
    height: 100px;
    object-fit: cover;
}

.team-photo .no-photo {
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #6c757d;
    border: 2px solid #dee2e6;
}
</style>

<?php include_once 'includes/footer.php'; ?>