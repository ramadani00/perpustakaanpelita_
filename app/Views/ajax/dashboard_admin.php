<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard Admin') ?></title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Main Style (anggota & admin dashboard) -->
    <link rel="stylesheet" href="<?= base_url('/style.css'); ?>">

    <!-- Header Template -->
    <?= $this->include('template/admin_header'); ?>
</head>
<body class="dashboard">
<div class="dashboard-container">
    <header class="dashboard-header">
        <div class="user-info">
            <div class="avatar">
                <?= esc(mb_substr(session()->get('admin_nama') ?? 'A', 0, 1)) ?>
            </div>
            <div class="user-details">
                <h2><?= esc(session()->get('admin_nama') ?? 'Admin') ?></h2>
                <p><?= esc(session()->get('admin_email') ?? 'admin@email.com') ?></p>
            </div>
        </div>
    </header>

    <?php if (session()->getFlashdata('flash_msg')): ?>
        <div class="alert alert-success">
            <p><i class="bi bi-check-circle-fill"></i> <?= esc(session()->getFlashdata('flash_msg')) ?></p>
        </div>
    <?php else: ?>
        <div class="alert alert-success">
            <p><i class="bi bi-check-circle-fill"></i> Selamat datang di Dashboard Admin Perpustakaan Digital.</p>
        </div>
    <?php endif; ?>

    <!-- Fitur Utama Admin -->
    <div class="dashboard-content">
        <div class="card text-center">
            <a href="<?= base_url('admin/buku/kelola_buku') ?>" class="text-decoration-none">
                <i class="bi bi-journal-bookmark" style="font-size:2.5rem;color:#007bff;"></i>
                <h4>Kelola Buku</h4>
                <p>Tambah, edit, hapus, dan kelola data buku perpustakaan.</p>
            </a>
        </div>
        <div class="card text-center">
            <a href="<?= base_url('admin/peminjaman/kelola_peminjaman') ?>" class="text-decoration-none">
                <i class="bi bi-arrow-left-right" style="font-size:2.5rem;color:#007bff;"></i>
                <h4>Kelola Peminjaman Buku</h4>
                <p>Kelola transaksi peminjaman dan pengembalian buku.</p>
            </a>
        </div>
        <div class="card text-center">
            <a href="<?= base_url('/admin/ajax/admin_index') ?>" class="text-decoration-none">
                <i class="bi bi-file-earmark-text" style="font-size:2.5rem;color:#007bff;"></i>
                <h4>Kelola Artikel</h4>
                <p>Kelola artikel, berita, dan informasi perpustakaan.</p>
            </a>
        </div>
    </div>

    <!-- Statistik -->
    <div class="dashboard-stats">
        <div class="stat-card">
            <i class="bi bi-journal-bookmark-fill"></i>
            <div class="number" id="stat-total-buku"><?= esc($total_buku ?? 0) ?></div>
            <div class="label">Total Buku Pepustakaan</div>
        </div>
        <div class="stat-card">
            <i class="bi bi-arrow-left-right"></i>
            <div class="number" id="stat-total-peminjaman"><?= esc($total_peminjaman ?? 0) ?></div>
            <div class="label">Buku Belum Dikembalikan</div>
        </div>
        <div class="stat-card">
            <i class="bi bi-arrow-repeat"></i>
            <div class="number" id="stat-total-dikembalikan"><?= esc($total_dikembalikan ?? 0) ?></div>
            <div class="label">Buku Dikembalikan</div>
        </div>
        <div class="stat-card">
            <i class="bi bi-arrow-bar-up"></i>
            <div class="number" id="stat-total-transaksi"><?= esc($total_transaksi ?? 0) ?></div>
            <div class="label">Total Peminjaman Buku</div>
        </div>
        <div class="stat-card">
            <i class="bi bi-person-check-fill"></i>
            <div class="number" id="stat-total-anggota"><?= esc($total_anggota ?? 0) ?></div>
            <div class="label">Total Anggota</div>
        </div>
        <div class="stat-card">
            <i class="bi bi-file-earmark-text"></i>
            <div class="number" id="stat-total-artikel"><?= esc($total_artikel ?? 0) ?></div>
            <div class="label">Total Artikel</div>
        </div>
    </div>

    <!-- Konten Dashboard -->
    <div class="dashboard-content">
        <div class="card">
            <h3><i class="bi bi-clock-history"></i> Aktivitas Terbaru</h3>
            <ul class="recent-activities" id="recentActivities">
                <!-- Data aktivitas admin akan diisi oleh JS -->
            </ul>
        </div>
    </div>


    <div class="dashboard-actions">
        <a href="<?= base_url('user/logout'); ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin logout?')">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<!-- Pagination AJAX -->
<div id="pagination" class="mt-4"></div>

<!-- JS & Plugin -->
<script>
    window.BASE_URL = "<?= base_url() ?>";
</script>
<script src="<?= base_url('assets/js/jquery-3.7.1.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?= base_url('app.js') ?>"></script>
<!-- Footer Template -->
<?= $this->include('template/admin_footer'); ?>
</body>
</html>
