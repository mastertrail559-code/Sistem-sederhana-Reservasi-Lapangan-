<?php
// ============================================
// SETUP DATABASE - Jalankan file ini 1x saja
// untuk membuat database, tabel, dan data awal
// ============================================

$host = 'localhost';
$user = 'root';
$pass = '5039';

// Koneksi ke MySQL (tanpa database)
$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

echo "<h2>🔧 Setup Database Merah Futsal</h2>";
echo "<hr>";

// 1. Buat Database
$sql = "CREATE DATABASE IF NOT EXISTS merah_futsal";
if (mysqli_query($conn, $sql)) {
    echo "✅ Database <strong>merah_futsal</strong> berhasil dibuat.<br>";
} else {
    echo "❌ Error membuat database: " . mysqli_error($conn) . "<br>";
}

// Pilih database
mysqli_select_db($conn, 'merah_futsal');
mysqli_set_charset($conn, "utf8");

// 2. Buat Tabel Admin
$sql = "CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB";

if (mysqli_query($conn, $sql)) {
    echo "✅ Tabel <strong>admin</strong> berhasil dibuat.<br>";
} else {
    echo "❌ Error: " . mysqli_error($conn) . "<br>";
}

// 3. Buat Tabel Booking
$sql = "CREATE TABLE IF NOT EXISTS booking (
    id_booking INT AUTO_INCREMENT PRIMARY KEY,
    nama_pemesan VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    lapangan VARCHAR(50) NOT NULL,
    tanggal_main DATE NOT NULL,
    jam_main TIME NOT NULL,
    durasi INT NOT NULL COMMENT 'Durasi dalam jam'
) ENGINE=InnoDB";

if (mysqli_query($conn, $sql)) {
    echo "✅ Tabel <strong>booking</strong> berhasil dibuat.<br>";
} else {
    echo "❌ Error: " . mysqli_error($conn) . "<br>";
}

// 4. Insert Data Admin Default
// Email: admin@merahfutsal.com | Password: admin123
$email    = 'admin@merahfutsal.com';
$password = password_hash('admin123', PASSWORD_DEFAULT);

// Cek apakah admin sudah ada
$check = mysqli_query($conn, "SELECT id FROM admin WHERE email = '$email'");
if (mysqli_num_rows($check) === 0) {
    $sql = "INSERT INTO admin (email, password) VALUES ('$email', '$password')";
    if (mysqli_query($conn, $sql)) {
        echo "✅ Admin default berhasil ditambahkan.<br>";
    } else {
        echo "❌ Error: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "ℹ️ Admin default sudah ada, dilewati.<br>";
}

// 5. Insert Data Contoh Booking
$check = mysqli_query($conn, "SELECT COUNT(*) as total FROM booking");
$row = mysqli_fetch_assoc($check);

if ($row['total'] == 0) {
    $bookings = [
        "('Ahmad Fadilah', '081234567890', 'Lapangan 1', '2026-06-01', '08:00:00', 2)",
        "('Budi Santoso', '082345678901', 'Lapangan 2', '2026-06-01', '10:00:00', 1)",
        "('Cahya Pratama', '083456789012', 'Lapangan 3', '2026-06-02', '14:00:00', 2)",
        "('Dian Permata', '084567890123', 'Lapangan 1', '2026-06-02', '16:00:00', 1)",
        "('Eko Saputra', '085678901234', 'Lapangan 2', '2026-06-03', '09:00:00', 2)"
    ];

    $sql = "INSERT INTO booking (nama_pemesan, no_hp, lapangan, tanggal_main, jam_main, durasi) VALUES " 
           . implode(", ", $bookings);
    
    if (mysqli_query($conn, $sql)) {
        echo "✅ Data contoh booking (5 data) berhasil ditambahkan.<br>";
    } else {
        echo "❌ Error: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "ℹ️ Data booking sudah ada, dilewati.<br>";
}

echo "<hr>";
echo "<h3>✅ Setup Selesai!</h3>";
echo "<p>Silakan login dengan:</p>";
echo "<ul>";
echo "<li><strong>Email:</strong> admin@merahfutsal.com</li>";
echo "<li><strong>Password:</strong> admin123</li>";
echo "</ul>";
echo "<p><a href='index.php' style='color: #C62828; font-weight: bold;'>➡️ Buka Halaman Login</a></p>";

mysqli_close($conn);
?>
