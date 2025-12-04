<?php
$page_title = "Semua Aktivitas - LET Lab";
include_once 'includes/header.php';
include_once 'includes/navbar.php';

include_once 'config/database.php';
include_once 'models/Activity.php';

function getYoutubeId($url) {
    if (empty($url)) return null;

    preg_match('/(youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/', $url, $matches);

    return isset($matches[2]) ? $matches[2] : null;
}

$database = new Database();
$db = $database->getConnection();
$activity = new Activity($db);

$stmt = $activity->read();
$all_activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="bg-primary text-white text-center py-5 mb-5">
    <div class="container">
        <h1 class="fw-bold display-5">Aktivitas & Kegiatan</h1>
        <p class="lead text-white-50">Dokumentasi lengkap riset, seminar, dan kegiatan di LET Lab.</p>
    </div>
</section>

<div class="container mb-5">
    
    <div class="row mb-5 justify-content-center">
        <div class="col-md-8">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="searchActivity" class="form-control border-start-0 border-end-0" placeholder="Cari judul aktivitas...">
                <select class="form-select border-start-0" id="filterType" style="max-width: 150px;">
                    <option value="all">Semua Tipe</option>
                    <option value="Research">Research</option>
                    <option value="Conference">Conference</option>
                    <option value="Workshop">Workshop</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Other">Lainnya</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row g-4" id="activityContainer">
        <?php 
        if(count($all_activities) > 0): 
            foreach($all_activities as $act):
                $videoId = getYoutubeId($act['link'] ?? '');
                
                // Thumbnail Logic
                $thumb = "";
                if (!empty($act['image_url'])) {
                    $thumb = htmlspecialchars($act['image_url']);
                } elseif ($videoId) {
                    $thumb = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
                }
        ?>
        <div class="col-md-4 activity-item" data-type="<?php echo htmlspecialchars($act['activity_type'] ?? 'Other'); ?>">
            <div class="card h-100 shadow-sm border-0 activity-card">
                <div class="position-relative overflow-hidden">
                    <?php if($thumb): ?>
                        <img src="<?php echo $thumb; ?>" 
                             class="card-img-top activity-img" 
                             alt="<?php echo htmlspecialchars($act['title']); ?>"
                             style="height: 220px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-dark d-flex align-items-center justify-content-center text-white" style="height: 220px;">
                            <i class="fas fa-video fa-3x"></i>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($videoId): ?>
                        <a href="#" class="play-overlay" 
                           data-bs-toggle="modal" 
                           data-bs-target="#videoModalPage" 
                           data-video-id="<?php echo $videoId; ?>">
                            <i class="fas fa-play-circle fa-5x text-white opacity-75"></i>
                        </a>
                    <?php endif; ?>
                    
                    <span class="badge bg-primary position-absolute top-0 end-0 m-3 shadow-sm">
                        <?php echo htmlspecialchars($act['activity_type'] ?? 'Activity'); ?>
                    </span>
                </div>

                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center text-muted small mb-2">
                        <i class="far fa-calendar-alt me-2"></i>
                        <?php echo date('d M Y', strtotime($act['activity_date'])); ?>
                        <span class="mx-2">•</span>
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <?php echo htmlspecialchars($act['location'] ?? 'Online'); ?>
                    </div>

                    <h5 class="card-title fw-bold text-dark mb-2 activity-title">
                        <?php echo htmlspecialchars($act['title']); ?>
                    </h5>
                    
                    <p class="card-text text-muted small mb-3 flex-grow-1">
                        <?php echo htmlspecialchars(substr($act['description'], 0, 120)); ?>...
                    </p>

                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($act['username'] ?? 'Admin'); ?>
                        </small>
                        
                        <?php if($videoId): ?>
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3"
                           data-bs-toggle="modal" 
                           data-bs-target="#videoModalPage"
                           data-video-id="<?php echo $videoId; ?>">
                            Tonton Video
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            endforeach; 
        else:
        ?>
        <div class="col-12 text-center py-5">
            <div class="text-muted">
                <i class="fas fa-film fa-4x mb-3 opacity-50"></i>
                <h5>Belum ada aktivitas yang diunggah.</h5>
            </div>
        </div>
        <?php endif; ?>

            <div class="text-center mt-5">
            <a href="index.php#news" class="btn btn-outline-primary px-4 rounded-pill">
                ← Kembali ke Home
            </a>
        </div>
    </div>
</div>

<div class="modal fade" id="videoModalPage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-black border-0">
            <div class="modal-header border-0 position-absolute w-100" style="z-index: 1055; background: transparent;">
                <button type="button" class="btn-close btn-close-white ms-auto me-2 mt-2 bg-white opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="pageVideoFrame" src="" title="YouTube video" allowfullscreen allow="autoplay"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Logic Pencarian & Filter
    const searchInput = document.getElementById('searchActivity');
    const filterType = document.getElementById('filterType');
    const items = document.querySelectorAll('.activity-item');

    function filterActivities() {
        const searchValue = searchInput.value.toLowerCase();
        const typeValue = filterType.value;

        items.forEach(item => {
            const title = item.querySelector('.activity-title').textContent.toLowerCase();
            const type = item.getAttribute('data-type');

            const matchesSearch = title.includes(searchValue);
            const matchesType = (typeValue === 'all' || type === typeValue);

            if (matchesSearch && matchesType) {
                item.classList.remove('d-none');
                item.classList.add('animate__fadeIn');
            } else {
                item.classList.add('d-none');
            }
        });
    }

    searchInput.addEventListener('keyup', filterActivities);
    filterType.addEventListener('change', filterActivities);


    // 2. LOGIC VIDEO PLAYER (PENTING!)
    const videoModalPage = document.getElementById('videoModalPage');
    const pageVideoFrame = document.getElementById('pageVideoFrame');

    if(videoModalPage){
        videoModalPage.addEventListener('show.bs.modal', function(event) {
            // Tombol yang diklik
            const button = event.relatedTarget;
            // Ambil ID Video dari atribut tombol
            const videoId = button.getAttribute('data-video-id');

            if (videoId) {
                // Set src iframe dengan autoplay
                pageVideoFrame.src = "https://www.youtube.com/embed/" + videoId + "?autoplay=1&rel=0&modestbranding=1";
            }
        });

        // Hentikan video saat modal ditutup
        videoModalPage.addEventListener('hidden.bs.modal', function () {
            pageVideoFrame.src = "";
        });
    }
});
</script>

<style>
/* Card Hover Effect */
.activity-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}
.activity-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

/* Zoom Image Effect */
.activity-img {
    transition: transform 0.5s ease;
}
.activity-card:hover .activity-img {
    transform: scale(1.05);
}

/* Play Button Overlay */
.play-overlay {
    position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.3);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity 0.3s; text-decoration: none;
    z-index: 10;
}
.activity-card:hover .play-overlay { opacity: 1; }
.play-overlay:hover i {
    transform: scale(1.1); transition: transform 0.2s; opacity: 1 !important;
}
</style>

<?php include_once 'includes/footer.php'; ?>