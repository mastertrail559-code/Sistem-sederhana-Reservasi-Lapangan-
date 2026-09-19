<?php
// ============================================
// Halaman Login
// ============================================
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$page_title = 'Login';
include 'includes/header.php';
?>

<div class="login-wrapper">
    <div class="login-card">
        <div class="logo-area">
            <div class="logo-icon">
                <i class="bi bi-dribbble"></i>
            </div>
            <h1>Merah Futsal</h1>
            <p>Masuk ke panel admin</p>
        </div>

        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert-merah mb-3">
                <i class="bi bi-exclamation-circle me-1"></i>
                <?= $_SESSION['login_error'] ?>
            </div>
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       placeholder="Masukkan email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-merah w-100 mt-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
