<?php
// ============================================
// Proses Hapus Booking
// ============================================
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

include 'config/database.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Cek apakah data ada
    $check = mysqli_query($conn, "SELECT id_booking FROM booking WHERE id_booking = $id");
    
    if (mysqli_num_rows($check) > 0) {
        $query = "DELETE FROM booking WHERE id_booking = $id";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Data booking berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "Data booking tidak ditemukan!";
    }
} else {
    $_SESSION['error'] = "ID booking tidak valid!";
}

header("Location: booking.php");
exit;
