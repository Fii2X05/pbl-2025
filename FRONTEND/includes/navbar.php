<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="index.php">
            <div class="logo-container">
                <img src="assets/images/logo.png" alt="LET Lab Logo" class="logo-img">
                <div class="logo-text">
                    <span class="logo-main">LET Lab</span>
                    <span class="logo-sub">Learning Engineering Technology</span>
                </div>
            </div>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Main Navigation -->
            <ul class="navbar-nav nav-main">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#about">
                        <i class="fas fa-info-circle"></i>
                        <span>About</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#research">
                        <i class="fas fa-flask"></i>
                        <span>Research</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#activities">
                        <i class="fas fa-chart-line"></i>
                        <span>Activities</span>
                    </a>
                </li>
                
                <!-- Booking Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo ($current_page == 'booking.php' || $current_page == 'booking_ruangan.php' || $current_page == 'booking_alat.php') ? 'active' : ''; ?>" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-calendar-check"></i>
                        <span>Booking</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="booking.php"><i class="fas fa-calendar-alt me-2"></i>All Bookings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="booking_ruangan.php"><i class="fas fa-building me-2"></i>Room Booking</a></li>
                        <li><a class="dropdown-item" href="booking_alat.php"><i class="fas fa-laptop me-2"></i>Equipment Booking</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="my_bookings.php"><i class="fas fa-list me-2"></i>My Bookings</a></li>
                        <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['role'] === 'admin'): ?>
                        <li><a class="dropdown-item" href="admin_booking.php"><i class="fas fa-cog me-2"></i>Manage Bookings</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="index.php#store">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Store</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#gallery">
                        <i class="fas fa-images"></i>
                        <span>Gallery</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#contact">
                        <i class="fas fa-envelope"></i>
                        <span>Contact</span>
                    </a>
                </li>
            </ul>
            
            <!-- User Actions -->
            <div class="navbar-actions">
                <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <div class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <?php if($_SESSION['role'] === 'admin'): ?>
                            <span class="badge admin-badge">Admin</span>
                        <?php endif; ?>
                    </div>
                    <div class="action-buttons">
                        <a href="dashboard.php" class="btn btn-dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="logout.php" class="btn btn-logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-login">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<style>
/* ===== DARK GRAY COLOR PALETTE ===== */
:root {
    /* Dark Gray Palette */
    --dark-bg: #2C2C2C;
    --dark-gray: #3A3A3A;
    --medium-gray: #4A4A4A;
    --light-gray: #5A5A5A;
    --text-light: #E0E0E0;
    --text-lighter: #F0F0F0;
    
    /* Accent Colors */
    --accent-blue: #4A90E2;
    --accent-blue-light: #5BA0F0;
    --accent-blue-dark: #3A80D2;
}

/* ===== NAVBAR BASE ===== */
.navbar-custom {
    padding: 0.6rem 0;
    min-height: 65px;
    background: var(--dark-bg);
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
    border-bottom: 1px solid var(--medium-gray);
}

.container {
    max-width: 1200px;
}

/* ===== LOGO STYLING ===== */
.logo-container {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo-img {
    height: 40px;
    width: auto;
    border-radius: 6px;
    filter: brightness(0.9);
}

.logo-text {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
}

.logo-main {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-lighter);
    letter-spacing: -0.5px;
}

.logo-sub {
    font-size: 0.75rem;
    color: var(--text-light);
    font-weight: 500;
}

/* ===== MAIN NAVIGATION ===== */
.nav-main {
    display: flex;
    align-items: center;
    gap: 2px;
    margin: 0 auto;
}

.nav-item {
    margin: 0;
}

.nav-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.5rem 0.9rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-light);
    border-radius: 8px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 75px;
    text-decoration: none;
    background: transparent;
}

.nav-link i {
    font-size: 1.1rem;
    margin-bottom: 4px;
    color: var(--text-light);
    transition: all 0.3s ease;
}

.nav-link span {
    font-size: 0.75rem;
    line-height: 1;
    font-weight: 500;
}

/* Nav Link States */
.nav-link:hover {
    background: var(--medium-gray);
    color: var(--text-lighter);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.nav-link:hover i {
    color: var(--accent-blue-light);
    transform: scale(1.1);
}

.nav-link.active {
    background: var(--accent-blue);
    color: var(--text-lighter);
    box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
    border: 1px solid var(--accent-blue-light);
}

.nav-link.active i {
    color: var(--text-lighter);
    transform: scale(1.1);
}

/* ===== DROPDOWN MENU ===== */
.dropdown-menu {
    border: none;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
    border-radius: 8px;
    padding: 8px;
    min-width: 200px;
    background: var(--dark-gray);
    border: 1px solid var(--medium-gray);
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    font-size: 0.85rem;
    border-radius: 6px;
    transition: all 0.2s ease;
    color: var(--text-light);
    font-weight: 500;
    background: transparent;
}

.dropdown-item i {
    width: 18px;
    text-align: center;
    font-size: 0.9rem;
    margin-right: 8px;
    color: var(--text-light);
}

.dropdown-item:hover,
.dropdown-item:focus {
    background: var(--accent-blue);
    color: var(--text-lighter);
    transform: translateX(3px);
}

.dropdown-item:hover i {
    color: var(--text-lighter);
}

.dropdown-divider {
    border-color: var(--medium-gray);
    margin: 6px 0;
}

/* ===== USER ACTIONS ===== */
.navbar-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: var(--medium-gray);
    border-radius: 8px;
    font-size: 0.85rem;
    color: var(--text-light);
    font-weight: 600;
    border: 1px solid var(--light-gray);
}

.user-info i {
    color: var(--accent-blue-light);
    font-size: 1rem;
}

.admin-badge {
    background: var(--accent-blue);
    color: var(--text-lighter);
    font-size: 0.65rem;
    padding: 3px 6px;
    border-radius: 4px;
    font-weight: 700;
    border: 1px solid var(--accent-blue-light);
}

.action-buttons {
    display: flex;
    gap: 8px;
}

/* Button Styles */
.btn-login,
.btn-dashboard,
.btn-logout {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid transparent;
    cursor: pointer;
}

.btn-login {
    background: var(--accent-blue);
    color: var(--text-lighter);
    border-color: var(--accent-blue-light);
}

.btn-dashboard {
    background: var(--medium-gray);
    color: var(--text-light);
    border: 1px solid var(--light-gray);
}

.btn-logout {
    background: var(--medium-gray);
    color: var(--text-light);
    border: 1px solid var(--light-gray);
}

.btn-login:hover {
    background: var(--accent-blue-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(74, 144, 226, 0.4);
    border-color: var(--accent-blue);
}

.btn-dashboard:hover {
    background: var(--accent-blue);
    color: var(--text-lighter);
    transform: translateY(-1px);
    box-shadow: 0 2px 10px rgba(74, 144, 226, 0.3);
    border-color: var(--accent-blue-light);
}

.btn-logout:hover {
    background: #E74C3C;
    color: var(--text-lighter);
    transform: translateY(-1px);
    border-color: #EC7063;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 991.98px) {
    .nav-main {
        margin: 1rem 0;
        gap: 0;
    }
    
    .nav-link {
        flex-direction: row;
        justify-content: flex-start;
        min-width: auto;
        padding: 0.75rem 1rem;
        margin: 2px 0;
        background: var(--medium-gray);
    }
    
    .nav-link i {
        margin-bottom: 0;
        margin-right: 10px;
        font-size: 1rem;
    }
    
    .nav-link span {
        font-size: 0.9rem;
    }
    
    .navbar-actions {
        flex-direction: column;
        width: 100%;
        gap: 10px;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--medium-gray);
    }
    
    .user-info {
        justify-content: center;
        width: 100%;
        background: var(--medium-gray);
    }
    
    .action-buttons {
        width: 100%;
        justify-content: center;
    }
    
    .btn-login,
    .btn-dashboard,
    .btn-logout {
        flex: 1;
        justify-content: center;
        max-width: 200px;
    }
}

/* Navbar Toggler */
.navbar-toggler {
    border: 1px solid var(--medium-gray);
    padding: 0.4rem 0.6rem;
    border-radius: 6px;
    background: var(--medium-gray);
}

.navbar-toggler:focus {
    box-shadow: 0 0 0 2px var(--accent-blue);
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(224, 224, 224, 0.9)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

/* Smooth transitions */
.navbar-collapse {
    transition: all 0.3s ease;
}

/* Active state indicator */
.nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    background: var(--accent-blue-light);
    border-radius: 50%;
    box-shadow: 0 0 8px var(--accent-blue-light);
}

/* Glass morphism effect for dark theme */
.navbar-custom {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

/* Hover glow effect */
.nav-link:hover {
    position: relative;
}

.nav-link:hover::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, var(--accent-blue), transparent);
    border-radius: 10px;
    z-index: -1;
    opacity: 0.3;
    animation: glow 2s ease-in-out infinite alternate;
}

@keyframes glow {
    from {
        opacity: 0.3;
    }
    to {
        opacity: 0.6;
    }
}
</style>