<?php
// ============================================
// Konfigurasi Database
// ============================================

$host   = 'localhost';
$user   = 'root';
$pass   = '5039';
$dbname = 'merah_futsal';

// Membuat koneksi ke database
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset UTF-8
mysqli_set_charset($conn, "utf8");
