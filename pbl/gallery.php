<?php
$page_title = "Galeri - LET Lab";
include_once 'includes/header.php';
include_once 'includes/navbar.php';

include_once 'config/database.php';
include_once 'models/Gallery.php';

$database = new Database();
$db = $database->getConnection();

$gallery = new Gallery($db);
$stmt = $gallery->read();
$gallery_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="bg-primary text-white text-center py-5 mb-5">
    <div class="container">
        <h1 class="fw-bold display-5">Galeri Kegiatan</h1>
        <p class="lead text-white-50">Dokumentasi aktivitas, fasilitas, dan momen terbaik di LET Lab.</p>
    </div>
</section>

<div class="container mb-5">
    
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="btn-group flex-wrap" role="group" aria-label="Gallery Filter">
                <button type="button" class="btn btn-outline-primary active filter-btn rounded-pill m-1 px-4" data-filter="all">Semua</button>
                <button type="button" class="btn btn-outline-primary filter-btn rounded-pill m-1 px-4" data-filter="events">Events</button>
                <button type="button" class="btn btn-outline-primary filter-btn rounded-pill m-1 px-4" data-filter="research">Research</button>
                <button type="button" class="btn btn-outline-primary filter-btn rounded-pill m-1 px-4" data-filter="facilities">Fasilitas</button>
                <button type="button" class="btn btn-outline-primary filter-btn rounded-pill m-1 px-4" data-filter="products">Produk</button>
                <button type="button" class="btn btn-outline-primary filter-btn rounded-pill m-1 px-4" data-filter="team">Tim</button>
            </div>
        </div>
    </div>

    <div class="row g-4" id="galleryContainer">
        <?php 
        if(count($gallery_items) > 0): 
            foreach($gallery_items as $item):
                if(($item['status'] ?? 'active') !== 'active') continue;
                
                $cat = strtolower($item['category'] ?? 'others');
        ?>
        <div class="col-md-6 col-lg-4 gallery-item" data-category="<?php echo $cat; ?>">
            <div class="card h-100 border-0 shadow-sm gallery-card">
                <div class="overflow-hidden position-relative cursor-pointer" 
                     onclick='showImageModal("<?php echo htmlspecialchars($item["image_url"]); ?>", "<?php echo htmlspecialchars($item["title"]); ?>", "<?php echo htmlspecialchars($item["description"] ?? ""); ?>")'>
                    
                    <?php if(!empty($item['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                             class="card-img-top gallery-img" 
                             alt="<?php echo htmlspecialchars($item['title']); ?>"
                             loading="lazy">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>

                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus text-white fa-2x"></i>
                    </div>

                    <span class="badge bg-primary position-absolute top-0 start-0 m-3 shadow-sm text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                        <?php echo htmlspecialchars($item['category']); ?>
                    </span>
                </div>
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title fw-bold text-dark mb-1"><?php echo htmlspecialchars($item['title']); ?></h5>
                            <p class="card-text text-muted small">
                                <?php echo htmlspecialchars(substr($item['description'] ?? '', 0, 100)); ?>...
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 border-top pt-2">
                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?php echo date('d M Y', strtotime($item['created_at'])); ?></small>
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
                <i class="fas fa-images fa-4x mb-3 opacity-50"></i>
                <h5>Belum ada galeri yang diunggah.</h5>
            </div>
        </div>
        <?php endif; ?>
    </div>
            <div class="text-center mt-5">
            <a href="index.php#news" class="btn btn-outline-primary px-4 rounded-pill">
                ← Kembali ke Home
            </a>
        </div>
</div>

<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 p-0 position-absolute end-0 top-0 m-2" style="z-index: 1055;">
                <button type="button" class="btn-close btn-close-white bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center position-relative">
                <img src="" id="modalImage" class="img-fluid rounded shadow-lg" style="max-height: 85vh;">
                <div class="bg-white p-3 rounded-bottom text-start mx-auto" style="max-width: 100%;">
                    <h4 id="modalTitle" class="fw-bold text-dark mb-1"></h4>
                    <p id="modalDesc" class="text-muted mb-0"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => {
                b.classList.remove('active'); 
                b.classList.remove('bg-primary');
                b.classList.remove('text-white');
            });
            btn.classList.add('active');
            btn.classList.add('bg-primary');
            btn.classList.add('text-white');

            const filterValue = btn.getAttribute('data-filter');

            galleryItems.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.classList.remove('d-none');
                    item.classList.add('animate__fadeIn'); // Optional animation class
                } else {
                    item.classList.add('d-none');
                }
            });
        });
    });

    var galleryModal = new bootstrap.Modal(document.getElementById('galleryModal'));

    function showImageModal(src, title, desc) {
        document.getElementById('modalImage').src = src;
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalDesc').textContent = desc;
        galleryModal.show();
    }
</script>

<style>
    .cursor-pointer {
        cursor: pointer;
    }
    
    .gallery-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    
    .gallery-img {
        height: 250px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-card:hover .gallery-img {
        transform: scale(1.05);
    }

    .gallery-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .gallery-card:hover .gallery-overlay {
        opacity: 1;
    }
</style>

<?php include_once 'includes/footer.php'; ?>