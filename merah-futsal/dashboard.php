<?php
// ============================================
// Halaman Dashboard
// ============================================
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

include 'config/database.php';

// Hitung total booking
$query_booking = "SELECT COUNT(*) as total FROM booking";
$result_booking = mysqli_query($conn, $query_booking);
$total_booking  = mysqli_fetch_assoc($result_booking)['total'];

// Total lapangan (tetap 3)
$total_lapangan = 3;

// Booking hari ini
$today = date('Y-m-d');
$query_today = "SELECT COUNT(*) as total FROM booking WHERE tanggal_main = '$today'";
$result_today = mysqli_query($conn, $query_today);
$booking_hari_ini = mysqli_fetch_assoc($result_today)['total'];

// 5 Booking terbaru
$query_terbaru = "SELECT * FROM booking ORDER BY id_booking DESC LIMIT 5";
$result_terbaru = mysqli_query($conn, $query_terbaru);

$page_title = 'Dashboard';
include 'includes/header.php';
?>

<div class="app-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-toggle-sidebar" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <span class="page-title">Dashboard</span>
            </div>
            <div class="admin-info">
                <span class="d-none d-sm-inline"><?= $_SESSION['admin_email'] ?></span>
                <div class="admin-avatar">A</div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-area">
            <!-- Greeting -->
            <div class="mb-4">
                <h4 style="font-weight: 700; color: var(--abu-gelap); margin-bottom: 4px;">
                    Selamat Datang, Admin! 👋
                </h4>
                <p style="color: var(--abu-medium); font-size: 14px; margin: 0;">
                    Berikut ringkasan data reservasi Merah Futsal hari ini.
                </p>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert-sukses mb-3">
                    <i class="bi bi-check-circle me-1"></i>
                    <?= $_SESSION['success'] ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon icon-merah">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stat-value"><?= $total_booking ?></div>
                        <div class="stat-label">Total Booking</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon icon-dark">
                            <i class="bi bi-dribbble"></i>
                        </div>
                        <div class="stat-value"><?= $total_lapangan ?></div>
                        <div class="stat-label">Total Lapangan</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon icon-green">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="stat-value"><?= $booking_hari_ini ?></div>
                        <div class="stat-label">Booking Hari Ini</div>
                    </div>
                </div>
            </div>

            <!-- Booking Terbaru -->
            <div class="data-card">
                <div class="data-card-header">
                    <h5><i class="bi bi-clock me-2"></i>Booking Terbaru</h5>
                    <a href="booking.php" class="btn btn-merah btn-sm" style="font-size: 13px; padding: 7px 16px;">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="data-card-body">
                    <div class="table-responsive">
                        <table class="table-merah">
                            <thead>
                                <tr>
                                    <th>Nama Pemesan</th>
                                    <th>Lapangan</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Durasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result_terbaru) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result_terbaru)): ?>
                                        <tr>
                                            <td style="font-weight: 500;"><?= htmlspecialchars($row['nama_pemesan']) ?></td>
                                            <td>
                                                <?php
                                                    $badge_class = 'badge-lap1';
                                                    if ($row['lapangan'] == 'Lapangan 2') $badge_class = 'badge-lap2';
                                                    if ($row['lapangan'] == 'Lapangan 3') $badge_class = 'badge-lap3';
                                                ?>
                                                <span class="badge-lapangan <?= $badge_class ?>">
                                                    <?= htmlspecialchars($row['lapangan']) ?>
                                                </span>
                                            </td>
                                            <td><?= date('d M Y', strtotime($row['tanggal_main'])) ?></td>
                                            <td><?= date('H:i', strtotime($row['jam_main'])) ?> WIB</td>
                                            <td><?= $row['durasi'] ?> Jam</td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <i class="bi bi-inbox"></i>
                                                <p>Belum ada data booking</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
