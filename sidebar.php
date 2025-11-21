<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="col-md-3 col-lg-2 sidebar">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="users.php">
                <i class="fas fa-users me-2"></i>Manajemen User
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="absensi.php">
                <i class="fas fa-clipboard-list me-2"></i>Absensi
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="booking.php">
                <i class="fas fa-calendar-check me-2"></i>Booking
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="analytics.php">
                <i class="fas fa-chart-bar me-2"></i>Analitik
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="settings.php">
                <i class="fas fa-cog me-2"></i>Pengaturan
            </a>
        </li>
    </ul>
</div>