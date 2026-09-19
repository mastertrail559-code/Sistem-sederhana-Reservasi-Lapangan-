<!-- ============================================
     Sidebar Navigation
     ============================================ -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-dribbble"></i>
        </div>
        <div class="brand-text">
            <h5>Merah Futsal</h5>
            <small>Panel Admin</small>
        </div>
    </div>
    <div class="sidebar-menu">
        <div class="menu-label">Menu Utama</div>
        <a href="dashboard.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>
        <a href="booking.php" class="menu-item <?= (basename($_SERVER['PHP_SELF']) == 'booking.php' || basename($_SERVER['PHP_SELF']) == 'tambah_booking.php' || basename($_SERVER['PHP_SELF']) == 'edit_booking.php') ? 'active' : '' ?>">
            <i class="bi bi-calendar-check-fill"></i>
            <span>Data Booking</span>
        </a>
        <div class="menu-label" style="margin-top: 20px;">Lainnya</div>
        <a href="logout.php" class="menu-item">
            <i class="bi bi-box-arrow-left"></i>
            <span>Logout</span>
        </a>
    </div>
</nav>
