<?php
session_start();

// Simulasi data admin (dalam implementasi nyata, ini berasal dari database)
$admin_name = "Administrator";
$admin_role = "Admin";

// Data statistik absensi (dalam implementasi nyata, ini berasal dari database)
$stats = [
    'total' => 85,
    'hadir' => 70,
    'terlambat' => 10,
    'tidak_hadir' => 5
];

// Data absensi (dalam implementasi nyata, ini berasal dari database)
$absensi_data = [
    [
        'id' => 1,
        'nama' => 'Ahmad Rizki',
        'nim' => '20210001',
        'kelas' => 'TI-01',
        'tanggal' => '2023-10-15',
        'status' => 'Hadir',
        'waktu' => '07:30'
    ],
    [
        'id' => 2,
        'nama' => 'Siti Aminah',
        'nim' => '20210002',
        'kelas' => 'TI-01',
        'tanggal' => '2023-10-15',
        'status' => 'Terlambat',
        'waktu' => '08:15'
    ],
    [
        'id' => 3,
        'nama' => 'Budi Santoso',
        'nim' => '20210003',
        'kelas' => 'TI-01',
        'tanggal' => '2023-10-15',
        'status' => 'Hadir',
        'waktu' => '07:45'
    ],
    [
        'id' => 4,
        'nama' => 'Maya Sari',
        'nim' => '20210004',
        'kelas' => 'TI-01',
        'tanggal' => '2023-10-15',
        'status' => 'Tidak Hadir',
        'waktu' => '-'
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Absensi - Information and Learning</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, #2c3e50, #34495e);
            color: white;
            padding: 20px 0;
            height: 100vh;
            position: fixed;
            overflow-y: auto;
        }

        .logo {
            text-align: center;
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .logo h1 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .logo p {
            font-size: 12px;
            opacity: 0.8;
        }

        .admin-info {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }

        .admin-details h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .admin-details p {
            font-size: 12px;
            opacity: 0.8;
        }

        .menu {
            padding: 0 15px;
        }

        .menu-item {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 5px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-item.active {
            background-color: #3498db;
        }

        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 28px;
        }

        .header-actions {
            display: flex;
            align-items: center;
        }

        .notification {
            position: relative;
            margin-right: 20px;
            font-size: 20px;
            color: #7f8c8d;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 14px;
            color: #7f8c8d;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .card-content {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        .card-footer {
            margin-top: 10px;
            font-size: 12px;
            color: #7f8c8d;
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }

        .filter-controls {
            display: flex;
            gap: 15px;
        }

        .filter-controls select, .filter-controls input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .status-hadir {
            color: #27ae60;
            font-weight: 600;
        }

        .status-terlambat {
            color: #f39c12;
            font-weight: 600;
        }

        .status-tidak-hadir {
            color: #e74c3c;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: background-color 0.3s;
        }

        .btn-edit {
            background-color: #3498db;
            color: white;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 10px;
            width: 500px;
            max-width: 90%;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #7f8c8d;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #2c3e50;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-cancel {
            background-color: #95a5a6;
            color: white;
        }

        .btn-save {
            background-color: #2ecc71;
            color: white;
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .dashboard-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar .logo h1, .sidebar .admin-details h3, .sidebar .admin-details p, .sidebar .menu-item span {
                display: none;
            }
            
            .sidebar .logo {
                padding: 15px 5px;
            }
            
            .sidebar .admin-info {
                justify-content: center;
                padding: 10px 5px;
            }
            
            .sidebar .menu-item {
                justify-content: center;
                padding: 15px 5px;
            }
            
            .sidebar .menu-item i {
                margin-right: 0;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .filter-controls {
                width: 100%;
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <h1>INFORMATION AND LEARNING</h1>
            <p>INDUSTRIES TECHNOLOGY</p>
        </div>
        
        <div class="admin-info">
            <div class="admin-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="admin-details">
                <h3><?php echo $admin_name; ?></h3>
                <p><?php echo $admin_role; ?> <span class="logout">(Logout)</span></p>
            </div>
        </div>
        
        <div class="menu">
            <div class="menu-item">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </div>
            <div class="menu-item active">
                <i class="fas fa-clipboard-list"></i>
                <span>Manajemen Absensi</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-chart-line"></i>
                <span>Activity</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-calendar-check"></i>
                <span>Booking</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Manajemen Absensi</h1>
            <div class="header-actions">
                <div class="notification">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <div class="user-profile">
                    <div class="admin-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span><?php echo $admin_name; ?></span>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="dashboard-cards">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Total Absensi</div>
                    <div class="card-icon" style="background-color: #3498db;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="card-content"><?php echo $stats['total']; ?></div>
                <div class="card-footer">Mahasiswa</div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Hadir</div>
                    <div class="card-icon" style="background-color: #2ecc71;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="card-content"><?php echo $stats['hadir']; ?></div>
                <div class="card-footer">Tepat waktu</div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Terlambat</div>
                    <div class="card-icon" style="background-color: #f39c12;">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="card-content"><?php echo $stats['terlambat']; ?></div>
                <div class="card-footer">Lebih dari 15 menit</div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Tidak Hadir</div>
                    <div class="card-icon" style="background-color: #e74c3c;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
                <div class="card-content"><?php echo $stats['tidak_hadir']; ?></div>
                <div class="card-footer">Tanpa keterangan</div>
            </div>
        </div>
        
        <!-- Absensi Table -->
        <div class="table-container">
            <div class="table-header">
                <div class="table-title">Data Absensi Mahasiswa</div>
                <div class="filter-controls">
                    <select id="filter-kategori">
                        <option value="all">Semua Kategori</option>
                        <option value="hadir">Hadir</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="tidak-hadir">Tidak Hadir</option>
                    </select>
                    <input type="date" id="filter-tanggal">
                    <button class="btn btn-edit" id="btn-tambah">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>
            </div>
            
            <table id="tabel-absensi">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Kelas</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($absensi_data as $data): ?>
                    <tr>
                        <td><?php echo $data['nama']; ?></td>
                        <td><?php echo $data['nim']; ?></td>
                        <td><?php echo $data['kelas']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($data['tanggal'])); ?></td>
                        <td>
                            <?php 
                            $status_class = '';
                            if($data['status'] == 'Hadir') $status_class = 'status-hadir';
                            if($data['status'] == 'Terlambat') $status_class = 'status-terlambat';
                            if($data['status'] == 'Tidak Hadir') $status_class = 'status-tidak-hadir';
                            ?>
                            <span class="<?php echo $status_class; ?>"><?php echo $data['status']; ?></span>
                        </td>
                        <td><?php echo $data['waktu']; ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-edit" onclick="editAbsensi(<?php echo $data['id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-delete" onclick="hapusAbsensi(<?php echo $data['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal Tambah/Edit Absensi -->
    <div class="modal" id="modal-absensi">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="modal-title">Tambah Data Absensi</div>
                <button class="close-modal" id="close-modal">&times;</button>
            </div>
            <form id="form-absensi">
                <input type="hidden" id="absensi-id">
                <div class="form-group">
                    <label for="nama">Nama Mahasiswa</label>
                    <input type="text" id="nama" required>
                </div>
                <div class="form-group">
                    <label for="nim">NIM</label>
                    <input type="text" id="nim" required>
                </div>
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <input type="text" id="kelas" required>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" id="tanggal" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" required>
                        <option value="">Pilih Status</option>
                        <option value="Hadir">Hadir</option>
                        <option value="Terlambat">Terlambat</option>
                        <option value="Tidak Hadir">Tidak Hadir</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="waktu">Waktu</label>
                    <input type="time" id="waktu">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" id="btn-batal">Batal</button>
                    <button type="submit" class="btn btn-save">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Modal functionality
        const modal = document.getElementById('modal-absensi');
        const btnTambah = document.getElementById('btn-tambah');
        const closeModal = document.getElementById('close-modal');
        const btnBatal = document.getElementById('btn-batal');
        const formAbsensi = document.getElementById('form-absensi');
        
        // Open modal for adding new data
        btnTambah.addEventListener('click', function() {
            document.getElementById('modal-title').textContent = 'Tambah Data Absensi';
            document.getElementById('form-absensi').reset();
            document.getElementById('absensi-id').value = '';
            modal.style.display = 'flex';
        });
        
        // Close modal
        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        btnBatal.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // Form submission
        formAbsensi.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // In a real application, you would send this data to the server
            const id = document.getElementById('absensi-id').value;
            const nama = document.getElementById('nama').value;
            const nim = document.getElementById('nim').value;
            const kelas = document.getElementById('kelas').value;
            const tanggal = document.getElementById('tanggal').value;
            const status = document.getElementById('status').value;
            const waktu = document.getElementById('waktu').value;
            
            if (id) {
                // Update existing record
                alert(`Data absensi ${nama} berhasil diperbarui!`);
            } else {
                // Add new record
                alert(`Data absensi ${nama} berhasil ditambahkan!`);
            }
            
            modal.style.display = 'none';
            
            // In a real application, you would refresh the table data here
        });
        
        // Edit absensi function
        function editAbsensi(id) {
            // In a real application, you would fetch the data from the server
            document.getElementById('modal-title').textContent = 'Edit Data Absensi';
            document.getElementById('absensi-id').value = id;
            
            // Example data - in real app, this would come from an AJAX request
            const sampleData = {
                nama: 'Ahmad Rizki',
                nim: '20210001',
                kelas: 'TI-01',
                tanggal: '2023-10-15',
                status: 'Hadir',
                waktu: '07:30'
            };
            
            document.getElementById('nama').value = sampleData.nama;
            document.getElementById('nim').value = sampleData.nim;
            document.getElementById('kelas').value = sampleData.kelas;
            document.getElementById('tanggal').value = sampleData.tanggal;
            document.getElementById('status').value = sampleData.status;
            document.getElementById('waktu').value = sampleData.waktu;
            
            modal.style.display = 'flex';
        }
        
        // Delete absensi function
        function hapusAbsensi(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                // In a real application, you would send a delete request to the server
                alert(`Data dengan ID ${id} berhasil dihapus!`);
                
                // In a real application, you would refresh the table data here
            }
        }
        
        // Filter functionality
        document.getElementById('filter-kategori').addEventListener('change', function() {
            filterTable();
        });
        
        document.getElementById('filter-tanggal').addEventListener('change', function() {
            filterTable();
        });
        
        function filterTable() {
            const kategori = document.getElementById('filter-kategori').value;
            const tanggal = document.getElementById('filter-tanggal').value;
            const rows = document.querySelectorAll('#tabel-absensi tbody tr');
            
            rows.forEach(row => {
                let showRow = true;
                const status = row.cells[4].textContent.trim();
                const rowTanggal = row.cells[3].textContent.trim();
                
                // Filter by kategori
                if (kategori !== 'all') {
                    if (kategori === 'hadir' && status !== 'Hadir') showRow = false;
                    if (kategori === 'terlambat' && status !== 'Terlambat') showRow = false;
                    if (kategori === 'tidak-hadir' && status !== 'Tidak Hadir') showRow = false;
                }
                
                // Filter by tanggal
                if (tanggal) {
                    const formattedTanggal = formatDateForComparison(tanggal);
                    if (rowTanggal !== formattedTanggal) showRow = false;
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        }
        
        function formatDateForComparison(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }
    </script>
</body>
</html>