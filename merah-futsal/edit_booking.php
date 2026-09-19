<?php
// ============================================
// Halaman Edit Booking
// ============================================
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

include 'config/database.php';

// Cek ID booking
if (!isset($_GET['id'])) {
    header("Location: booking.php");
    exit;
}

$id = (int) $_GET['id'];

// Ambil data booking berdasarkan ID
$query  = "SELECT * FROM booking WHERE id_booking = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['error'] = "Data booking tidak ditemukan!";
    header("Location: booking.php");
    exit;
}

$data = mysqli_fetch_assoc($result);

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama_pemesan']);
    $hp       = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $lapangan = mysqli_real_escape_string($conn, $_POST['lapangan']);
    $tanggal  = mysqli_real_escape_string($conn, $_POST['tanggal_main']);
    $jam      = mysqli_real_escape_string($conn, $_POST['jam_main']);
    $durasi   = (int) $_POST['durasi'];

    // Validasi: tanggal tidak boleh sebelum hari ini
    if ($tanggal < date('Y-m-d')) {
        $error = "Tanggal main tidak boleh sebelum hari ini!";
    } else {
        // Validasi: cek tabrakan jam (kecuali booking ini sendiri)
        $jam_mulai_baru   = strtotime($jam);
        $jam_selesai_baru = strtotime("+$durasi hours", $jam_mulai_baru);

        $cek_query = "SELECT jam_main, durasi FROM booking 
                      WHERE lapangan = '$lapangan' AND tanggal_main = '$tanggal' AND id_booking != $id";
        $cek_result = mysqli_query($conn, $cek_query);

        $bentrok = false;
        while ($row = mysqli_fetch_assoc($cek_result)) {
            $jam_mulai_ada   = strtotime($row['jam_main']);
            $jam_selesai_ada = strtotime("+{$row['durasi']} hours", $jam_mulai_ada);

            if ($jam_mulai_baru < $jam_selesai_ada && $jam_selesai_baru > $jam_mulai_ada) {
                $bentrok = true;
                $jam_tampil = date('H:i', $jam_mulai_ada) . ' - ' . date('H:i', $jam_selesai_ada);
                break;
            }
        }

        if ($bentrok) {
            $error = "Jadwal bentrok! $lapangan sudah dibooking pada $jam_tampil WIB di tanggal tersebut.";
        } else {
            $query = "UPDATE booking SET 
                      nama_pemesan = '$nama',
                      no_hp = '$hp',
                      lapangan = '$lapangan',
                      tanggal_main = '$tanggal',
                      jam_main = '$jam',
                      durasi = $durasi
                      WHERE id_booking = $id";

            if (mysqli_query($conn, $query)) {
                $_SESSION['success'] = "Data booking berhasil diperbarui!";
                header("Location: booking.php");
                exit;
            } else {
                $error = "Gagal memperbarui data: " . mysqli_error($conn);
            }
        }
    }
}

$page_title = 'Edit Booking';
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
                <span class="page-title">Edit Booking</span>
            </div>
            <div class="admin-info">
                <span class="d-none d-sm-inline"><?= $_SESSION['admin_email'] ?></span>
                <div class="admin-avatar">A</div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-area">
            <div class="mb-3">
                <a href="booking.php" style="font-size: 13px; color: var(--abu-medium); font-weight: 500;">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Data Booking
                </a>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert-merah mb-3">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <div class="form-card">
                <h5 style="font-size: 16px; font-weight: 700; margin-bottom: 24px; color: var(--abu-gelap);">
                    <i class="bi bi-pencil-square me-2" style="color: var(--merah-primary);"></i>Form Edit Booking #<?= $id ?>
                </h5>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
                        <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" 
                               value="<?= htmlspecialchars($data['nama_pemesan']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">Nomor HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" 
                               value="<?= htmlspecialchars($data['no_hp']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="lapangan" class="form-label">Lapangan</label>
                        <select class="form-select" id="lapangan" name="lapangan" required>
                            <option value="Lapangan 1" <?= $data['lapangan'] == 'Lapangan 1' ? 'selected' : '' ?>>Lapangan 1</option>
                            <option value="Lapangan 2" <?= $data['lapangan'] == 'Lapangan 2' ? 'selected' : '' ?>>Lapangan 2</option>
                            <option value="Lapangan 3" <?= $data['lapangan'] == 'Lapangan 3' ? 'selected' : '' ?>>Lapangan 3</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_main" class="form-label">Tanggal Main</label>
                            <input type="date" class="form-control" id="tanggal_main" name="tanggal_main" 
                                   value="<?= $data['tanggal_main'] ?>" min="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jam_main" class="form-label">Jam Main</label>
                            <input type="time" class="form-control" id="jam_main" name="jam_main" 
                                   value="<?= date('H:i', strtotime($data['jam_main'])) ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="durasi" class="form-label">Durasi Sewa (Jam)</label>
                        <select class="form-select" id="durasi" name="durasi" required>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>" <?= $data['durasi'] == $i ? 'selected' : '' ?>>
                                    <?= $i ?> Jam
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-merah">
                            <i class="bi bi-check-lg me-1"></i>Perbarui
                        </button>
                        <a href="booking.php" class="btn btn-outline-merah">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
