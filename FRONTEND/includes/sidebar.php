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
            <a class="nav-link <?php echo ($current_page == 'partners.php') ? 'active' : ''; ?>" href="partners.php">
                <i class="fas fa-handshake me-2"></i>Partners
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'team.php') ? 'active' : ''; ?>" href="team.php">
                <i class="fas fa-users me-2"></i>Team
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'products.php') ? 'active' : ''; ?>" href="products.php">
                <i class="fas fa-box me-2"></i>Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'news.php') ? 'active' : ''; ?>" href="news.php">
                <i class="fas fa-newspaper me-2"></i>News
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'gallery.php') ? 'active' : ''; ?>" href="gallery.php">
                <i class="fas fa-images me-2"></i>Gallery
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'activity.php') ? 'active' : ''; ?>" href="activity.php">
                <i class="fas fa-chart-line me-2"></i>Activity
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'booking.php') ? 'active' : ''; ?>" href="booking.php">
                <i class="fas fa-calendar-check me-2"></i>Booking
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'absensi.php') ? 'active' : ''; ?>" href="absensi.php">
                <i class="fas fa-clipboard-list me-2"></i>Absensi
            </a>
        </li>
    </ul>
</div>
