<?php
$page_title = "LET Lab - Home";
include_once 'includes/header.php';
include_once 'includes/navbar.php';

include_once 'config/database.php';
include_once 'models/News.php';
include_once 'models/Partner.php';
include_once 'models/Products.php'; 
include_once 'models/Team.php';

function getYoutubeId($url) {
    if (empty($url)) return null;

    preg_match('/(youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/', $url, $matches);

    return isset($matches[2]) ? $matches[2] : null;
}


if(file_exists('models/Products.php')) {
    include_once 'models/Products.php';
} elseif(file_exists('models/Product.php')) {
    include_once 'models/Product.php';
} else {
    die("Error: File model Product tidak ditemukan.");
}

$database = new Database();
$db = $database->getConnection();

$news = new News($db);
$recent_news = $news->read();

$product = new Product($db);
$products = $product->read();

$partner = new Partner($db);
$partners = $partner->read();

$team = new Team($db);
$team_members = $team->read();

if(file_exists('models/Activity.php')){
    include_once 'models/Activity.php';
    $activity_model = new Activity($db);
    $recent_activities = $activity_model->read();
}

?>

<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Learning Engineering Technology Laboratory</h1>
        <p class="lead">Berdasarkan inovasi dan riset dalam rekayasa pembelajaran untuk membangun ekosistem pendidikan berkualitas.</p>
        <a href="#about" class="btn btn-primary btn-lg mt-3">Pelajari Lebih Lanjut</a>
    </div>
</section>

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

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-2">Tim Dosen Kami</h2>
        <p class="text-center text-muted mb-5">Para ahli dan pengajar berpengalaman di Learning Engineering Technology Laboratory</p>
        
        <div id="dosenCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php 
                $dosen_list = [];
                $temp_members = $team_members->fetchAll(PDO::FETCH_ASSOC);
                foreach($temp_members as $member) {
                    if($member['status'] == 'active') {
                        $dosen_list[] = $member;
                    }
                }
                
                $total_slides = ceil(count($dosen_list) / 3);
                for($i = 0; $i < $total_slides; $i++):
                ?>
                    <button type="button" data-bs-target="#dosenCarousel" data-bs-slide-to="<?php echo $i; ?>" 
                            <?php echo $i == 0 ? 'class="active" aria-current="true"' : ''; ?> 
                            aria-label="Slide <?php echo $i + 1; ?>"></button>
                <?php endfor; ?>
            </div>
            
            <div class="carousel-inner">
                <?php 
                if(count($dosen_list) > 0):
                    $chunks = array_chunk($dosen_list, 3);
                    foreach($chunks as $index => $chunk):
                ?>
                <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                    <div class="row justify-content-center">
                        <?php foreach($chunk as $dosen): 
                            // Decode social_links
                            $social = !empty($dosen['social_links']) ? json_decode($dosen['social_links'], true) : [];
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <?php if(!empty($dosen['photo'])): ?>
                                            <img src="<?php echo htmlspecialchars($dosen['photo']); ?>" 
                                                 alt="<?php echo htmlspecialchars($dosen['name']); ?>" 
                                                 class="rounded-circle border border-3 border-primary shadow" 
                                                 style="width: 120px; height: 120px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-light border border-3 border-primary d-flex align-items-center justify-content-center mx-auto shadow-sm" 
                                                 style="width: 120px; height: 120px;">
                                                <i class="fas fa-user fa-3x text-secondary"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h5 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($dosen['name']); ?></h5>
                                    <p class="text-primary small fw-semibold mb-3">
                                        <i class="fas fa-chalkboard-teacher me-1"></i>
                                        <?php echo htmlspecialchars($dosen['position']); ?>
                                    </p>
                                    
                                    <?php if(!empty($dosen['bio'])): ?>
                                        <p class="text-muted small mb-3" style="min-height: 60px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?php echo htmlspecialchars($dosen['bio']); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-center gap-2 mb-2 flex-wrap">
                                            <?php if(!empty($dosen['email'])): ?>
                                                <a href="mailto:<?php echo htmlspecialchars($dosen['email']); ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Email">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if(!empty($dosen['phone'])): ?>
                                                <a href="tel:<?php echo htmlspecialchars($dosen['phone']); ?>" 
                                                   class="btn btn-sm btn-outline-success" title="Phone">
                                                    <i class="fas fa-phone"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if(isset($social['linkedin'])): ?>
                                                <a href="<?php echo htmlspecialchars($social['linkedin']); ?>" 
                                                   target="_blank" class="btn btn-sm btn-outline-primary" title="LinkedIn">
                                                    <i class="fab fa-linkedin"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if(isset($social['twitter'])): ?>
                                                <a href="<?php echo htmlspecialchars($social['twitter']); ?>" 
                                                   target="_blank" class="btn btn-sm btn-outline-info" title="Twitter">
                                                    <i class="fab fa-twitter"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if(isset($social['website'])): ?>
                                                <a href="<?php echo htmlspecialchars($social['website']); ?>" 
                                                   target="_blank" class="btn btn-sm btn-outline-secondary" title="Website">
                                                    <i class="fas fa-globe"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if(isset($social['sinta']) || isset($social['google_scholar']) || isset($social['researchgate']) || isset($social['orcid']) || isset($social['scopus'])): ?>
                                        <div class="d-flex justify-content-center gap-1 flex-wrap mt-2">
                                            <?php if(isset($social['sinta'])): ?>
                                                <a href="<?php echo htmlspecialchars($social['sinta']); ?>" 
                                                   target="_blank" class="btn btn-sm btn-warning text-white" title="SINTA Profile">
                                                    <i class="fas fa-graduation-cap"></i> <small>SINTA</small>
                                                </a>
                                            <?php endif; ?>
                                            <?php if(isset($social['google_scholar'])): ?>
                                                <a href="<?php echo htmlspecialchars($social['google_scholar']); ?>" 
                                                   target="_blank" class="btn btn-sm btn-primary" title="Google Scholar">
                                                    <i class="fas fa-book"></i> <small>Scholar</small>
                                                </a>
                                            <?php endif; ?>
                                            <?php if(isset($social['researchgate'])): ?>
                                                <a href="<?php echo htmlspecialchars($social['researchgate']); ?>" 
                                                   target="_blank" class="btn btn-sm btn-success" title="ResearchGate">
                                                    <i class="fab fa-researchgate"></i> <small>RG</small>
                                                </a>
                                            <?php endif; ?>
                                            <?php if(isset($social['orcid'])): ?>
                                                <a href="<?php echo htmlspecialchars($social['orcid']); ?>" 
                                                   target="_blank" class="btn btn-sm btn-info text-white" title="ORCID">
                                                    <i class="fas fa-id-card"></i> <small>ORCID</small>
                                                </a>
                                            <?php endif; ?>
                                            <?php if(isset($social['scopus'])): ?>
                                                <a href="<?php echo htmlspecialchars($social['scopus']); ?>" 
                                                   target="_blank" class="btn btn-sm btn-danger" title="Scopus">
                                                    <i class="fas fa-flask"></i> <small>Scopus</small>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php 
                    endforeach;
                else: 
                ?>
                <div class="carousel-item active">
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data dosen tersedia</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if(count($dosen_list) > 3): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#dosenCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#dosenCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="daftar_team.php" class="btn btn-outline-primary">
                <i class="fas fa-users me-2"></i>Lihat Semua Tim
            </a>
        </div>
    </div>
</section>

<!-- Partners Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Partner Kami</h2>
        <div class="row justify-content-center">
            <?php 
            $count = 0;
            while($row = $partners->fetch(PDO::FETCH_ASSOC)): 
                if($row['status'] == 'active' && $count < 6):
                    $count++;
            ?>
            <div class="col-6 col-md-4 col-lg-2 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-center p-3">
                        <?php if(!empty($row['logo'])): ?>
                            <img src="<?php echo htmlspecialchars($row['logo']); ?>" 
                                 alt="<?php echo htmlspecialchars($row['name']); ?>" 
                                 class="img-fluid" 
                                 style="max-height: 60px; object-fit: contain;">
                        <?php else: ?>
                            <div class="text-center">
                                <i class="fas fa-handshake fa-2x text-muted mb-2"></i>
                                <small class="d-block"><?php echo htmlspecialchars($row['name']); ?></small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php 
                endif;
            endwhile; 
            ?>
        </div>
    </div>
</section>

<section id="news" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Berita & Artikel Terbaru</h2>
        <div class="row">

            <?php 
            $count = 0;
            while($article = $recent_news->fetch(PDO::FETCH_ASSOC)): 
                if($article['status'] == 'published' && $count < 3):
                    $count++;
            ?>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if(!empty($article['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($article['image_url']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($article['title']); ?>"
                             style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-newspaper fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <span class="badge bg-primary mb-2">
                            <?php echo htmlspecialchars($article['category']); ?>
                        </span>

                        <h5 class="card-title">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </h5>

                        <p class="card-text text-muted small">
                            <?php 
                                echo htmlspecialchars(substr(strip_tags($article['content']), 0, 100)); 
                            ?>...
                        </p>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="far fa-calendar"></i> 
                                <?php echo date('d M Y', strtotime($article['publish_date'])); ?>
                            </small>

                            <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#newsModal<?php echo $article['id']; ?>">
                                Baca Selengkapnya
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- MODAL BERITA -->
            <div class="modal fade" id="newsModal<?php echo $article['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <?php if(!empty($article['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($article['image_url']); ?>" 
                                     class="img-fluid rounded mb-3"
                                     alt="<?php echo htmlspecialchars($article['title']); ?>">
                            <?php endif; ?>

                            <div class="text-muted mb-3 small">
                                <i class="far fa-calendar"></i> 
                                <?php echo date('d M Y', strtotime($article['publish_date'])); ?>
                                &nbsp; • &nbsp;
                                <span class="badge bg-primary">
                                    <?php echo htmlspecialchars($article['category']); ?>
                                </span>
                            </div>

                            <p style="white-space: pre-line;">
                                <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                            </p>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <?php 
                endif;
            endwhile; 
            ?>

        </div>

        <div class="text-center mt-4">
            <a href="news_detail.php" class="btn btn-outline-primary">Lihat Semua Berita</a>
        </div>
    </div>
</section>


<?php 
include_once 'models/Activity.php'; 
$activity_model = new Activity($db);
$recent_activities = $activity_model->read(); 
?>

<section id="activities" class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold">Aktivitas & Kegiatan Kami</h2>
                <p class="text-muted">Dokumentasi kegiatan, seminar, dan riset terbaru dari LET Lab.</p>
            </div>
        </div>

        <div class="row">
            <?php 
            $act_count = 0;
            if($recent_activities->rowCount() > 0):
                while($act = $recent_activities->fetch(PDO::FETCH_ASSOC)): 
                    if($act_count < 3):
                        $act_count++;
                        
                        $videoId = getYoutubeId($act['link'] ?? '');
                        
                        $thumb = "";

                        if ($videoId) {
                            $thumb = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
                        } elseif (!empty($act['image_url'])) {
                            $thumb = htmlspecialchars($act['image_url']);
                        }

            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 activity-card">
                    <div class="position-relative">
                        <?php if($thumb): ?>
                            <img src="<?php echo $thumb; ?>" 
                                 class="card-img-top" 
                                 style="height: 220px; object-fit: cover;" 
                                 alt="Activity Thumbnail">
                        <?php else: ?>
                            <div class="bg-dark d-flex align-items-center justify-content-center text-white" style="height: 220px;">
                                <i class="fas fa-video fa-3x"></i>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($videoId): ?>
                            <a href="javascript:void(0)" 
                                class="play-overlay" 
                                data-bs-toggle="modal" 
                                data-bs-target="#videoModalHome"
                                data-video-id="<?php echo $videoId; ?>">

                                <i class="fas fa-play-circle fa-4x text-white opacity-75"></i>
                            </a>
                        <?php endif; ?>
                        
                        <span class="badge bg-primary position-absolute top-0 end-0 m-3">
                            <?php echo htmlspecialchars($act['activity_type'] ?? 'Activity'); ?>
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-center text-muted small mb-2">
                            <i class="far fa-calendar-alt me-2"></i>
                            <?php echo date('d M Y', strtotime($act['activity_date'])); ?>
                        </div>

                        <h5 class="card-title fw-bold text-dark mb-2">
                            <?php echo htmlspecialchars($act['title']); ?>
                        </h5>
                        
                        <p class="card-text text-muted small">
                            <?php echo htmlspecialchars(substr($act['description'], 0, 90)); ?>...
                        </p>
                    </div>
                </div>
            </div>
            <?php 
                    endif;
                endwhile; 
            else:
            ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada aktivitas terbaru.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-4">
            <a href="activities.php" class="btn btn-outline-primary">
                Lihat Semua Aktivitas <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<div class="modal fade" id="videoModalHome" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-black border-0">
            <div class="modal-header border-0 position-absolute w-100" style="z-index: 1055; background: transparent;">
                <button type="button" class="btn-close btn-close-white ms-auto me-2 mt-2 bg-white opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="homeVideoFrame" src="" title="YouTube video" allowfullscreen allow="autoplay"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoModalHome = document.getElementById('videoModalHome');
    const homeVideoFrame = document.getElementById('homeVideoFrame');

    if(videoModalHome){
        videoModalHome.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const videoId = button.getAttribute('data-video-id');

            if (videoId) {
                homeVideoFrame.src = "https://www.youtube.com/embed/" + videoId + "?autoplay=1&rel=0&modestbranding=1";
            }
        });

        videoModalHome.addEventListener('hidden.bs.modal', function () {
            homeVideoFrame.src = "";
        });
    }
});
</script>


<style>
.activity-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}
.activity-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.play-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
    text-decoration: none;
}
.activity-card:hover .play-overlay {
    opacity: 1;
}
.play-overlay:hover i {
    transform: scale(1.1);
    transition: transform 0.2s;
    opacity: 1 !important;
}
</style>


<section id="store" class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold">Produk & Aplikasi</h2>
                <p class="text-muted">Inovasi teknologi pembelajaran hasil riset kami.</p>
            </div>
        </div>

        <div class="row">
            <?php 
            $count = 0;
            if($products && $products->rowCount() > 0):
                while($prod = $products->fetch(PDO::FETCH_ASSOC)): 
                    $status = $prod['status'] ?? 'active';
                    if($status == 'active' && $count < 4):
                        $count++;
            ?>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm border-0 product-card">
                    <div class="position-relative overflow-hidden">
                        <?php if(!empty($prod['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($prod['image_url']); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($prod['name']); ?>"
                                 style="height: 180px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-white d-flex align-items-center justify-content-center border-bottom" style="height: 180px;">
                                <i class="fas fa-cube fa-3x text-muted opacity-25"></i>
                            </div>
                        <?php endif; ?>
                        
                        <span class="badge bg-primary position-absolute top-0 start-0 m-3 shadow-sm">
                            <?php echo htmlspecialchars($prod['category'] ?? 'App'); ?>
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark mb-2">
                            <?php echo htmlspecialchars($prod['name']); ?>
                        </h5>
                        
                        <p class="card-text text-muted small mb-3 flex-grow-1">
                            <?php echo htmlspecialchars(substr($prod['description'] ?? '', 0, 80)); ?>...
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                            <div>
                                <?php if(isset($prod['price']) && $prod['price'] > 0): ?>
                                    <span class="text-primary fw-bold">Rp <?php echo number_format($prod['price'], 0, ',', '.'); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Gratis</span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if(!empty($prod['link_demo'])): ?>
                                <a href="<?php echo htmlspecialchars($prod['link_demo']); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-download me-1"></i> Get
                                </a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-light rounded-pill px-3" disabled>Detail</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                    endif;
                endwhile; 
            else:
            ?>
                <div class="col-12 text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-box-open fa-3x mb-3 opacity-50"></i>
                        <p>Belum ada produk yang ditampilkan.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
</style>

<!-- Gallery Section -->
<section id="gallery" class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold">Galeri Kami</h2>
                <p class="text-muted">Dokumentasi kegiatan dan fasilitas laboratorium.</p>
            </div>
        </div>
        
        <div class="row">
            <?php 
            if(file_exists('models/Gallery.php')) {
                include_once 'models/Gallery.php';
                $gallery_model = new Gallery($db);
                $gallery_items = $gallery_model->read();
                
                $count = 0;
                if($gallery_items->rowCount() > 0):
                    while($item = $gallery_items->fetch(PDO::FETCH_ASSOC)): 
                        if(($item['status'] ?? 'active') == 'active' && $count < 3):
                            $count++;
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 gallery-card">
                    <div class="overflow-hidden position-relative">
                        <?php if(!empty($item['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                 class="card-img-top gallery-img" 
                                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                                 style="height: 250px; object-fit: cover; transition: transform 0.5s;">
                        <?php else: ?>
                            <div class="bg-secondary d-flex align-items-center justify-content-center text-white" style="height: 250px;">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                        <?php endif; ?>
                        
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 shadow-sm">
                            <?php echo ucfirst($item['category'] ?? 'General'); ?>
                        </span>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-2"><?php echo htmlspecialchars($item['title']); ?></h5>
                        <p class="card-text text-muted small">
                            <?php echo htmlspecialchars(substr($item['description'] ?? '', 0, 80)); ?>...
                        </p>
                    </div>
                </div>
            </div>
            <?php 
                        endif;
                    endwhile; 
                else:
            ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada galeri yang diunggah.</p>
                </div>
            <?php 
                endif;
            }
            ?>
        </div>

        <div class="text-center mt-4">
            <a href="gallery.php" class="btn btn-outline-primary px-4 rounded-pill">
                Lihat Semua Galeri <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<style>
.gallery-card:hover .gallery-img {
    transform: scale(1.1); 
}
.gallery-card {
    overflow: hidden; 
    transition: transform 0.3s, box-shadow 0.3s;
}
.gallery-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
</style>


<!-- Contact Section -->
<section id="contact" class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold">Hubungi Kami</h2>
                <p class="text-muted">Kami siap mendengar masukan dan pertanyaan Anda.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-5 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Informasi Kontak</h4>
                        
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-map-marker-alt fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="fw-bold mb-1">Alamat</h6>
                                <p class="text-muted mb-0">Politeknik Negeri Malang<br>Jl. Soekarno Hatta No.9, Malang</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-phone fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="fw-bold mb-1">Telepon</h6>
                                <p class="text-muted mb-0">(0341) 404424</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-envelope fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="fw-bold mb-1">Email</h6>
                                <p class="text-muted mb-0">let@polinema.ac.id</p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="flex-shrink-0 btn-square bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-globe fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="fw-bold mb-1">Website</h6>
                                <p class="text-muted mb-0">www.letlab.polinema.ac.id</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="fw-bold mb-4 text-center">Get in Touch</h2>
                        <p class="text-center text-muted mb-4">Salah satu tim kami akan segera menghubungi Anda.</p>
                        
                        <form action="process_guestbook.php" method="POST">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nama Lengkap" required>
                                <label for="full_name">Nama Lengkap</label>
                            </div>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-md">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="phone" name="phone_number" placeholder="Nomor HP" required>
                                        <label for="phone">Nomor HP / WhatsApp</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="institution" name="institution" placeholder="Asal Instansi" required>
                                <label for="institution">Asal Instansi / Kampus</label>
                            </div>

                            <div class="form-floating mb-4">
                                <textarea class="form-control" placeholder="Pesan Anda" id="message" name="message" style="height: 150px" required></textarea>
                                <label for="message">Pesan / Pertanyaan</label>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary btn-lg" type="submit" name="submit_guestbook">
                                    Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
#dosenCarousel .carousel-control-prev-icon,
#dosenCarousel .carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    padding: 20px;
}

#dosenCarousel .carousel-indicators button {
    background-color: #6c757d;
}

#dosenCarousel .carousel-indicators button.active {
    background-color: #0d6efd;
}

#dosenCarousel .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

#dosenCarousel .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
}
</style>

<?php include_once 'includes/footer.php'; ?>