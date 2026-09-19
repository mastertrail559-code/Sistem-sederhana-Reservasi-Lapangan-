<?php
// ============================================
// Proses Login
// ============================================
session_start();
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Cari admin berdasarkan email
    $query  = "SELECT * FROM admin WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $admin['password'])) {
            // Login berhasil
            $_SESSION['admin_id']    = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            header("Location: dashboard.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Email atau password salah!";
            header("Location: index.php");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Email atau password salah!";
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
