-- ============================================
-- DATABASE: Merah Futsal
-- Sistem Informasi Reservasi Lapangan Futsal
-- ============================================

CREATE DATABASE IF NOT EXISTS merah_futsal;
USE merah_futsal;

-- ============================================
-- Tabel Admin
-- ============================================
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- ============================================
-- Tabel Booking
-- ============================================
CREATE TABLE IF NOT EXISTS booking (
    id_booking INT AUTO_INCREMENT PRIMARY KEY,
    nama_pemesan VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    lapangan VARCHAR(50) NOT NULL,
    tanggal_main DATE NOT NULL,
    jam_main TIME NOT NULL,
    durasi INT NOT NULL COMMENT 'Durasi dalam jam'
) ENGINE=InnoDB;

-- ============================================
-- Data Awal Admin
-- Email: admin@merahfutsal.com
-- Password: admin123
-- ============================================
INSERT INTO admin (email, password) VALUES 
('admin@merahfutsal.com', '$2y$10$8K1p/a0dR1xqM8k4z5rKCOzGnJHLyO0.h5h9h5h9h5h9h5h9h5h9h');

-- ============================================
-- Data Contoh Booking
-- ============================================
INSERT INTO booking (nama_pemesan, no_hp, lapangan, tanggal_main, jam_main, durasi) VALUES
('Ahmad Fadilah', '081234567890', 'Lapangan 1', '2026-06-01', '08:00:00', 2),
('Budi Santoso', '082345678901', 'Lapangan 2', '2026-06-01', '10:00:00', 1),
('Cahya Pratama', '083456789012', 'Lapangan 3', '2026-06-02', '14:00:00', 2),
('Dian Permata', '084567890123', 'Lapangan 1', '2026-06-02', '16:00:00', 1),
('Eko Saputra', '085678901234', 'Lapangan 2', '2026-06-03', '09:00:00', 2);
