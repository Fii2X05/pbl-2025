<style>
    /* Styling Dropdown Profile ala Edge/Modern */
    .profile-dropdown {
        position: relative;
        display: inline-block;
    }
    
    .profile-trigger {
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: background 0.2s;
        border: 2px solid rgba(255,255,255,0.3);
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .profile-trigger:hover {
        background: rgba(255,255,255,0.1);
        border-color: #fff;
    }

    /* Menu Pop-up */
    .profile-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 60px;
        width: 300px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        z-index: 1050;
        padding: 20px;
        border: 1px solid #e0e0e0;
        animation: slideDown 0.2s ease;
    }

    .profile-menu.active {
        display: block;
    }

    /* Header Profil */
    .profile-header {
        display: flex;
        align-items: center;
        gap: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 15px;
    }

    .profile-avatar {
        width: 60px;
        height: 60px;
        background: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #555;
    }

    .profile-info h6 { margin: 0; font-weight: 700; color: #333; font-size: 16px; }
    .profile-info span { font-size: 13px; color: #888; }
    .status-badge { font-size: 11px; color: #28a745; display: flex; align-items: center; gap: 4px; margin-top: 2px; }

    /* Link Menu */
    .profile-links a, .profile-links button {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 12px 15px;
        color: #444;
        text-decoration: none;
        border-radius: 8px;
        transition: background 0.2s;
        border: none;
        background: none;
        text-align: left;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .profile-links a:hover, .profile-links button:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    .profile-links i { width: 30px; font-size: 16px; }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <div class="logo-container">
                <img src="assets/img/logo_let.png" alt="Logo" class="logo-img" onerror="this.style.display='none'">
                <div class="logo-text ms-2">
                    <span class="logo-main">LET Lab</span>
                    <span class="logo-sub d-block" style="font-size: 0.7rem;">Learning Engineering Technology</span>
                </div>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#about"><i class="fas fa-info-circle"></i> About</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#news"><i class="fas fa-newspaper me-1"></i> News</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#activities"><i class="fas fa-chart-line"></i> Activities</a></li>
                <li class="nav-item"><a class="nav-link" href="visitor_booking.php"><i class="fas fa-calendar-check"></i> Booking</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#store"><i class="fas fa-shopping-cart"></i> Store</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#gallery"><i class="fas fa-images"></i> Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#contact"><i class="fas fa-envelope"></i> Contact</a></li>
            </ul>

            <div class="navbar-actions ms-lg-3">
                <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    
                    <div class="profile-dropdown">
                        <div class="profile-trigger" onclick="toggleProfileMenu()" id="profileTrigger">
                            <i class="fas fa-user text-white fs-5"></i>
                        </div>

                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <div class="profile-avatar">
                                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                </div>
                                <div class="profile-info">
                                    <h6><?php echo htmlspecialchars($_SESSION['username']); ?></h6>
                                    <span><?php echo ucfirst($_SESSION['role']); ?> Account</span>
                                    <div class="status-badge"><i class="fas fa-circle" style="font-size: 8px;"></i> Active</div>
                                </div>
                            </div>

                            <div class="profile-links">
                                
                                <?php if($_SESSION['role'] === 'admin'): ?>
                                    <a href="admin_dashboard.php">
                                        <i class="fas fa-tachometer-alt text-primary"></i> Admin Dashboard
                                    </a>
                                <?php endif; ?>

                                <?php if($_SESSION['role'] === 'member'): ?>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#attendanceModal" onclick="toggleProfileMenu()">
                                        <i class="fas fa-clipboard-check text-success"></i> Presensi Harian
                                    </button>
                                <?php endif; ?>
                                
                                <?php if($_SESSION['role'] === 'dosen'): ?>
                                    <a href="#">
                                        <i class="fas fa-user-tie text-warning"></i> Profil Dosen
                                    </a>
                                <?php endif; ?>

                                <hr class="my-2">
                                <a href="logout.php" class="text-danger">
                                    <i class="fas fa-sign-out-alt"></i> Sign Out
                                </a>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <a href="login.php" class="btn btn-nav rounded-pill px-4">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'member'): ?>
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-camera me-2"></i>Presensi & Upload Bukti</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="process_attendance.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <h4 class="fw-bold"><?php echo date('l, d F Y'); ?></h4>
                        <p class="text-muted small">Silakan upload bukti kehadiran (Selfie/Suasana Lab).</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold mb-2 small text-uppercase text-secondary">Bukti Foto</label>
                        <input type="file" class="form-control" name="photo_proof" accept="image/*" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Format: JPG, PNG, JPEG. Max 2MB.</small>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold mb-2 small text-uppercase text-secondary">Lokasi / Kegiatan</label>
                        <textarea class="form-control bg-light" name="location_note" rows="2" placeholder="Contoh: Sedang praktikum di Lab Jaringan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4">Kirim Presensi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    function toggleProfileMenu() {
        const menu = document.getElementById('profileMenu');
        menu.classList.toggle('active');
    }

    document.addEventListener('click', function(event) {
        const menu = document.getElementById('profileMenu');
        const trigger = document.getElementById('profileTrigger');
        
        if (menu && trigger && !menu.contains(event.target) && !trigger.contains(event.target)) {
            menu.classList.remove('active');
        }
    });
</script>