<nav class="navbar navbar-expand-lg navbar-modern fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/img/logo_let.png" alt="Logo" class="brand-logo-img me-2" onerror="this.style.display='none'">
            <div class="d-flex flex-column">
                <span style="line-height: 1;">LET Lab</span>
                <small style="font-size: 0.65rem; font-weight: 400; color: #666;">Politeknik Negeri Malang</small>
            </div>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#news">News</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#activities">Activities</a></li>
                <li class="nav-item"><a class="nav-link" href="visitor_booking.php">Booking</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#store">Store</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#gallery">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#contact">Contact</a></li>
            </ul>

            <div class="navbar-actions ms-lg-3">
                <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <div class="profile-dropdown">
                        <button class="profile-trigger" type="button" id="profileTrigger">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-user"></i>
                            </div>
                        </button>
                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <div class="profile-avatar"><i class="fas fa-user"></i></div>
                                <div class="profile-info">
                                    <h6><?php echo htmlspecialchars($_SESSION['username']); ?></h6>
                                    <span><?php echo ucfirst($_SESSION['role']); ?></span>
                                </div>
                            </div>
                            <div class="profile-links">
                                <?php if($_SESSION['role'] === 'admin'): ?>
                                    <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt text-primary"></i> Admin Dashboard</a>
                                <?php endif; ?>
                                <?php if($_SESSION['role'] === 'member'): ?>
                                    <button type="button" class="dropdown-btn" data-bs-toggle="modal" data-bs-target="#attendanceModal">
                                        <i class="fas fa-clipboard-check text-success"></i> Presensi Harian
                                    </button>
                                <?php endif; ?>
                                <hr class="my-2">
                                <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt"></i> Sign Out
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        Login <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

        <div class="navbar-actions ms-auto">
            <div class="admin-info me-3 text-white">
                <i class="fas fa-user-shield me-1"></i>
                <span class="admin-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
            <a href="#" class="btn btn-sm btn-outline-light" class="text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
        </div>

<style>
/* --- VARIABLES --- */
:root {
    --primary-color: #2c3e50;
    --secondary-color: #3498db;
    --accent-color: #e74c3c;
    --text-dark: #2d3436;
    --text-muted: #636e72;
    --bg-light: #f8f9fa;
}


body {
    padding-top: 85px; 
}

.navbar-modern {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
    padding: 15px 0;
    transition: all 0.3s ease;
    z-index: 1040; 
}

.navbar-modern .navbar-brand {
    font-weight: 800;
    color: var(--primary-color);
    letter-spacing: -0.5px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.navbar-modern .nav-link {
    font-weight: 600;
    color: var(--text-dark) !important;
    margin: 0 5px;
    padding: 8px 15px !important;
    border-radius: 25px;
    transition: all 0.3s ease;
    position: relative;
}

.navbar-modern .nav-link:hover,
.navbar-modern .nav-link.active {
    color: var(--secondary-color) !important;
    background: rgba(52, 152, 219, 0.1);
}

.brand-logo-img {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    object-fit: cover;
    box-shadow: 0 4px 10px rgba(52, 152, 219, 0.2);
}

.profile-dropdown {
    position: relative;
}

.profile-trigger {
    background: transparent;
    border: none;
    padding: 0;
    cursor: pointer;
    transition: transform 0.2s;
}

.profile-trigger:hover {
    transform: scale(1.05);
}

.profile-trigger:focus {
    outline: none;
}

.profile-menu {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    min-width: 280px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1050;
}

.profile-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.profile-header {
    padding: 20px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.profile-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--secondary-color), #5dade2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.profile-info h6 {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-dark);
}

.profile-info span {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.profile-links {
    padding: 10px;
}

.profile-links a,
.profile-links .dropdown-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    color: var(--text-dark);
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s;
    font-size: 0.95rem;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

.profile-links a:hover,
.profile-links .dropdown-btn:hover {
    background: rgba(52, 152, 219, 0.1);
    color: var(--secondary-color);
}

.profile-links a.text-danger:hover {
    background: rgba(231, 76, 60, 0.1);
    color: var(--accent-color);
}

.profile-links hr {
    margin: 10px 0;
    opacity: 0.1;
}

@media (max-width: 991px) {
    .profile-menu {
        right: -15px;
        min-width: 260px;
    }
    
    body {
        padding-top: 75px;
    }
}

html {
    scroll-padding-top: 100px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileTrigger = document.getElementById('profileTrigger');
    const profileMenu = document.getElementById('profileMenu');
    
    if(profileTrigger && profileMenu) {
        profileTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('show');
        });
        
        document.addEventListener('click', function(e) {
            if (!profileTrigger.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.remove('show');
            }
        });
        
        profileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                profileMenu.classList.remove('show');
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if(href === currentPage || href === './' + currentPage) {
            link.classList.add('active');
        }
    });
});
</script>