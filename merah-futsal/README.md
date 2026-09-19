# Merah Futsal — Sistem Reservasi Lapangan

Aplikasi web PHP untuk manajemen booking lapangan futsal. Admin dapat login, mengelola data booking (CRUD + pencarian), serta melihat detail pesanan melalui modal.

## Tech Stack
- **Backend:** PHP native + MySQLi
- **Frontend:** HTML + CSS (Bootstrap icons) + JavaScript (modal interaktif)
- **Database:** MySQL (`merah_futsal`)

## Struktur Database
- `admin` — akun pengelola (`email`, `password` hash)
- `booking` — data reservasi (`nama_pemesan`, `no_hp`, `lapangan`, `tanggal_main`, `jam_main`, `durasi`)

## Fitur Utama
- Login admin (session-based)
- CRUD booking + pencarian nama pemesan
- Modal detail & konfirmasi hapus
- Sidebar & topbar responsif
- Badge lapangan (1, 2, 3)

## Instalasi
```bash
# 1. Import database
mysql -u root -p merah_futsal < database.sql

# 2. Konfigurasi
Edit config/database.php sesuai kredensial MySQL Anda.

# 3. Jalankan
php -S localhost:8000
# Atau akses via XAMPP / Laragon
```

## Akun Default
- Email: `admin@merahfutsal.com`
- Password: `admin123`
- **Catatan:** Password di database sudah di-hash (`$2y$10$...`), bukan plain text.

## File Penting
- `config/database.php` — konfigurasi koneksi
- `includes/` — header, footer, sidebar
- `assets/css/style.css` — styling aplikasi
- `database.sql` — skema & data awal

## Lisensi
Pribadi / Internal — sesuaikan sebelum penggunaan komersial.
