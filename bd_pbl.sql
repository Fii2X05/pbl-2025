-- =============================================
-- 1. ENUMS (Tipe Data Khusus untuk Pilihan)
-- =============================================

-- Peran User
CREATE TYPE user_role AS ENUM ('admin', 'member');

-- Tipe Mahasiswa (Untuk membedakan menu Absensi)
CREATE TYPE student_type AS ENUM ('regular', 'magang', 'skripsi');

-- Status Peminjaman
CREATE TYPE loan_status AS ENUM ('pending', 'approved', 'rejected', 'returned', 'overdue');

-- Status Publikasi Konten
CREATE TYPE content_status AS ENUM ('draft', 'published', 'archived');

-- =============================================
-- 2. TABEL USER & OTENTIKASI (D1)
-- =============================================

CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    nim VARCHAR(20) UNIQUE, -- Penting: Muncul otomatis saat Absen
    institution VARCHAR(100), -- Asal Kampus/Sekolah
    email VARCHAR(100) UNIQUE,
    role user_role DEFAULT 'member',
    student_type student_type DEFAULT 'regular', -- Jika 'magang'/'skripsi', menu absen muncul
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- 3. TABEL STATISTIK PENGUNJUNG (D3)
-- Fitur: Visitor Counter (Online Now, Today, Total)
-- =============================================

CREATE TABLE visitor_logs (
    log_id SERIAL PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL, -- Support IPv4 & IPv6
    user_agent TEXT, -- Info Browser/Device
    page_url VARCHAR(255), -- Halaman yang dikunjungi
    access_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- 4. TABEL MASTER DATA INVENTARIS (D5 & D6)
-- Fitur: Kelola Alat & Ruangan
-- =============================================

-- D5: Alat (Punya Stok)
CREATE TABLE tools (
    tool_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    total_stock INT DEFAULT 0, -- Stok total aset
    available_stock INT DEFAULT 0, -- Stok yang bisa dipinjam saat ini
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- D6: Ruangan (Status Tersedia/Tidak)
CREATE TABLE rooms (
    room_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    capacity INT,
    description TEXT,
    is_available BOOLEAN DEFAULT TRUE, -- Jika False, jadi abu-abu di form visitor
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- 5. TABEL TRANSAKSI (D4 & D7)
-- Fitur: Absensi & Peminjaman
-- =============================================

-- D4: Absensi Mahasiswa (Check In/Out)
CREATE TABLE attendance_logs (
    log_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    date DATE DEFAULT CURRENT_DATE,
    check_in_time TIME,  -- Diisi saat klik tombol "Absen Datang"
    check_out_time TIME, -- Diisi saat klik tombol "Absen Pulang"
    photo_proof VARCHAR(255), -- Bukti Foto Selfie
    location_note TEXT, -- Catatan lokasi/kegiatan
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- D7: Transaksi Peminjaman (Alat & Ruang)
CREATE TABLE loans (
    loan_id SERIAL PRIMARY KEY,
    -- Data Peminjam (Visitor bisa input manual, Member otomatis)
    borrower_name VARCHAR(100) NOT NULL,
    borrower_contact VARCHAR(50) NOT NULL, -- No HP / WA
    borrower_email VARCHAR(100),
    institution VARCHAR(100),
    
    -- Detail Barang/Ruang
    item_type VARCHAR(20) CHECK (item_type IN ('tool', 'room')), -- Pembeda
    item_id INT NOT NULL, -- ID dari tabel tools atau rooms
    qty INT DEFAULT 1, -- Hanya berlaku untuk 'tool'
    
    -- Waktu Pinjam
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP NOT NULL,
    
    -- Status Approval Admin
    status loan_status DEFAULT 'pending',
    admin_note TEXT, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Tamu (Guest Book) 
CREATE TABLE guest_books (
    guest_id SERIAL PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    institution VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone_number VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- 6. TABEL KONTEN WEBSITE (D2)
-- Fitur: Berita, Carousel, Partner, Tim
-- =============================================

CREATE TABLE posts (
    post_id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE,
    content TEXT,
    thumbnail_url VARCHAR(255),
    status content_status DEFAULT 'published',
    author_id INT REFERENCES users(user_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE carousel_banners (
    banner_id SERIAL PRIMARY KEY,
    title VARCHAR(100),
    image_url VARCHAR(255) NOT NULL,
    link_url VARCHAR(255), 
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0
);

CREATE TABLE partners (
    partner_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo_url VARCHAR(255),
    website_url VARCHAR(255)
);

CREATE TABLE products (
    product_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    link_demo VARCHAR(255)
);

CREATE TABLE team_members (
    member_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100), 
    photo_url VARCHAR(255),
    social_links JSONB 
);