<?php
$page_title = "LET Lab - Home";
include_once 'includes/header.php';
include_once 'includes/navbar.php';

// Include database and models
include_once 'config/database.php';
include_once 'models/Activity.php';

// Database connection
$database = new Database();
$db = $database->getConnection();

// Get recent activities
$activity = new Activity($db);
$recent_activities = $activity->getRecentActivities(3);
?>

<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Learning Engineering Technology Laboratory</h1>
        <p class="lead">Berdasarkan inovasi dan riset dalam rekayasa pembelajaran untuk membangun ekosistem pendidikan berkualitas.</p>
        <a href="#about" class="btn btn-primary btn-lg mt-3">Pelajari Lebih Lanjut</a>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h2 class="mb-4">Tentang Kami</h2>
                <p class="lead">Learning Engineering Technology Laboratory adalah pusat inovasi dan riset dalam teknologi pembelajaran yang berkomitmen untuk meningkatkan kualitas pendidikan melalui pemanfaatan berbagai data dari teknologi.</p>
                <p>Kami membangun sistem pendukung lengkap berdasarkan perilaku belajar siswa. Pendekatan komprehensif kami mencakup aplikasi pembelajaran, analitik, integrasi AI, sistem dukungan adaptif, gamifikasi, dan pemantauan manajemen.</p>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="img-fluid rounded" alt="Team Collaboration">
            </div>
        </div>
    </div>
</section>

<!-- Research Section -->
<section id="research" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Penelitian Kami</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Smart Monitoring in Learning (SMILE)</h5>
                        <p class="card-text">Sistem pemantauan cerdas untuk memantau dan menganalisis proses pembelajaran siswa secara real-time.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Smart Adaptive Technology in Education (SArt)</h5>
                        <p class="card-text">Teknologi adaptif yang menyesuaikan konten pembelajaran berdasarkan kemampuan dan kebutuhan individu siswa.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Gamification</h5>
                        <p class="card-text">Penerapan elemen permainan dalam pembelajaran untuk meningkatkan motivasi dan keterlibatan siswa.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Tim Kami</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Audi</h5>
                        <p class="card-text">Promotion & Service, Network and Innovation (SMILE)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Tamahiro</h5>
                        <p class="card-text">Advanced & Bio-Micro Automation (SMILE)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Mingi</h5>
                        <p class="card-text">Technical Development & Business Development</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Activities Section -->
<section id="activities" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Aktivitas Penelitian Terbaru</h2>
        <div class="row">
            <?php while($row = $recent_activities->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['activity_type']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <small class="text-muted">Oleh: <?php echo htmlspecialchars($row['username']); ?> | <?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Store Section -->
<section id="store" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Aplikasi Pembelajaran</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Viat Map Application</h5>
                        <p class="card-text">Aplikasi VIAT-map (Visual Arguments Toulmin) membantu membaca pemahaman dengan menggunakan Konsep Argument Toulmin.</p>
                        <a href="#" class="btn btn-primary">Coba Sekarang</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">PseudoLearn Application</h5>
                        <p class="card-text">Media pembelajaran rekonstruksi algoritma pseudocode dengan menggunakan pendekatan Element Fill-in-Blank Problems dalam pemrograman Java.</p>
                        <a href="#" class="btn btn-primary">Coba Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section id="gallery" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Galeri Kami</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="card-img-top" alt="Conference">
                    <div class="card-body">
                        <h5 class="card-title">ICCE 2024</h5>
                        <p class="card-text">International Conference on Computers in Education</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="card-img-top" alt="Research">
                    <div class="card-body">
                        <h5 class="card-title">Penelitian Lapangan</h5>
                        <p class="card-text">Aktivitas penelitian di lingkungan pendidikan nyata</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="card-img-top" alt="Workshop">
                    <div class="card-body">
                        <h5 class="card-title">Workshop Pembelajaran</h5>
                        <p class="card-text">Sesi workshop untuk guru dan pendidik</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="text-center mb-5">Hubungi Kami</h2>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-map-marker-alt fa-2x mb-3 text-primary"></i>
                                <h5>Alamat</h5>
                                <p>Politeknik Negeri Malang<br>Jl. Soekarno Hatta No.9, Malang</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-phone fa-2x mb-3 text-primary"></i>
                                <h5>Telepon</h5>
                                <p>(0341) 404424</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-envelope fa-2x mb-3 text-primary"></i>
                                <h5>Email</h5>
                                <p>let@polinema.ac.id</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-globe fa-2x mb-3 text-primary"></i>
                                <h5>Website</h5>
                                <p>www.letlab.polinema.ac.id</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once 'includes/footer.php'; ?>