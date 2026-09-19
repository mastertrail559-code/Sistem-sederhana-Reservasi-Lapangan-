<?php
// ============================================
// Halaman Data Booking (CRUD + Search + Detail)
// ============================================
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

include 'config/database.php';

// Fitur Pencarian
$search = '';
$where  = '';
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where  = "WHERE nama_pemesan LIKE '%$search%'";
}

// Query data booking
$query  = "SELECT * FROM booking $where ORDER BY id_booking DESC";
$result = mysqli_query($conn, $query);

$page_title = 'Data Booking';
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
                <span class="page-title">Data Booking</span>
            </div>
            <div class="admin-info">
                <span class="d-none d-sm-inline"><?= $_SESSION['admin_email'] ?></span>
                <div class="admin-avatar">A</div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-area">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert-sukses mb-3">
                    <i class="bi bi-check-circle me-1"></i>
                    <?= $_SESSION['success'] ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert-merah mb-3">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    <?= $_SESSION['error'] ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="data-card">
                <div class="data-card-header">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <h5 class="mb-0"><i class="bi bi-table me-2"></i>Daftar Booking</h5>
                        <a href="tambah_booking.php" class="btn btn-merah btn-sm" style="font-size: 13px; padding: 7px 16px;">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Booking
                        </a>
                    </div>
                    <!-- Search -->
                    <form method="GET" action="booking.php" class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" placeholder="Cari nama pemesan..." 
                               value="<?= htmlspecialchars($search) ?>">
                    </form>
                </div>

                <div class="data-card-body">
                    <?php if (!empty($search)): ?>
                        <div style="padding: 12px 24px; background: #FFF8E1; border-bottom: 1px solid #FFF0B3; font-size: 13px; color: #F57F17;">
                            <i class="bi bi-info-circle me-1"></i>
                            Menampilkan hasil pencarian: "<strong><?= htmlspecialchars($search) ?></strong>" 
                            (<?= mysqli_num_rows($result) ?> data)
                            <a href="booking.php" style="margin-left: 8px; color: var(--merah-primary); font-weight: 600;">Reset</a>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table-merah">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pemesan</th>
                                    <th>No HP</th>
                                    <th>Lapangan</th>
                                    <th>Tanggal Main</th>
                                    <th>Jam</th>
                                    <th>Durasi</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td style="font-weight: 500;"><?= htmlspecialchars($row['nama_pemesan']) ?></td>
                                            <td><?= htmlspecialchars($row['no_hp']) ?></td>
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
                                            <td style="text-align: center; white-space: nowrap;">
                                                <!-- Tombol Detail -->
                                                <button class="btn-action btn-detail" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalDetail"
                                                        onclick="showDetail(
                                                            '<?= $row['id_booking'] ?>',
                                                            '<?= htmlspecialchars($row['nama_pemesan'], ENT_QUOTES) ?>',
                                                            '<?= htmlspecialchars($row['no_hp'], ENT_QUOTES) ?>',
                                                            '<?= htmlspecialchars($row['lapangan'], ENT_QUOTES) ?>',
                                                            '<?= date('d M Y', strtotime($row['tanggal_main'])) ?>',
                                                            '<?= date('H:i', strtotime($row['jam_main'])) ?> WIB',
                                                            '<?= $row['durasi'] ?>'
                                                        )"
                                                        title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <!-- Tombol Edit -->
                                                <a href="edit_booking.php?id=<?= $row['id_booking'] ?>" 
                                                   class="btn-action btn-edit" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <!-- Tombol Hapus -->
                                                <button class="btn-action btn-hapus" 
                                                        onclick="konfirmasiHapus(<?= $row['id_booking'] ?>)" 
                                                        title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <i class="bi bi-inbox"></i>
                                                <p>
                                                    <?php if (!empty($search)): ?>
                                                        Tidak ada data yang cocok dengan pencarian.
                                                    <?php else: ?>
                                                        Belum ada data booking. Silakan tambah data baru.
                                                    <?php endif; ?>
                                                </p>
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

<!-- ============================================
     Modal Detail Booking
     ============================================ -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel">
                    <i class="bi bi-info-circle me-2" style="color: var(--merah-primary);"></i>Detail Booking
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="detail-row">
                    <div class="detail-label">ID Booking</div>
                    <div class="detail-value" id="detail-id"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nama Pemesan</div>
                    <div class="detail-value" id="detail-nama"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nomor HP</div>
                    <div class="detail-value" id="detail-hp"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Lapangan</div>
                    <div class="detail-value" id="detail-lapangan"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Tanggal Main</div>
                    <div class="detail-value" id="detail-tanggal"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Jam Main</div>
                    <div class="detail-value" id="detail-jam"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Durasi Sewa</div>
                    <div class="detail-value" id="detail-durasi"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-merah btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================
     Modal Konfirmasi Hapus
     ============================================ -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size: 15px;">
                    <i class="bi bi-exclamation-triangle me-2" style="color: var(--merah-primary);"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="font-size: 14px; color: var(--abu-medium);">
                Apakah Anda yakin ingin menghapus data booking ini? Data yang dihapus tidak dapat dikembalikan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-merah btn-sm" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="btnKonfirmasiHapus" class="btn btn-merah btn-sm" style="font-size: 13px; padding: 7px 16px;">
                    <i class="bi bi-trash me-1"></i>Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Tampilkan detail booking di modal
    function showDetail(id, nama, hp, lapangan, tanggal, jam, durasi) {
        document.getElementById('detail-id').textContent = '#' + id;
        document.getElementById('detail-nama').textContent = nama;
        document.getElementById('detail-hp').textContent = hp;
        document.getElementById('detail-lapangan').textContent = lapangan;
        document.getElementById('detail-tanggal').textContent = tanggal;
        document.getElementById('detail-jam').textContent = jam;
        document.getElementById('detail-durasi').textContent = durasi + ' Jam';
    }

    // Konfirmasi hapus dengan modal
    function konfirmasiHapus(id) {
        document.getElementById('btnKonfirmasiHapus').href = 'hapus_booking.php?id=' + id;
        var modal = new bootstrap.Modal(document.getElementById('modalHapus'));
        modal.show();
    }
</script>

<?php include 'includes/footer.php'; ?>
