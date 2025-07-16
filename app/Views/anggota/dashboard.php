<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard Anggota') ?></title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Main Style (anggota & admin dashboard) -->
    <link rel="stylesheet" href="<?= base_url('/style.css'); ?>">

<!-- Header Template -->
<?= $this->include('template/anggota_header'); ?>
</head>
<div class="dashboard-container">
    <header class="dashboard-header">
        <div class="user-info">
            <div class="avatar">
                <?= esc(mb_substr(session()->get('anggota_nama') ?? 'A', 0, 1)) ?>
            </div>
            <div class="user-details">
                <h2><?= esc(session()->get('anggota_nama') ?? 'Anggota') ?></h2>
                <p><?= esc(session()->get('anggota_email') ?? 'anggota@email.com') ?></p>
            </div>
        </div>
    </header>

    <?php if (session()->getFlashdata('flash_msg')): ?>
        <div class="alert alert-success">
            <p><i class="fas fa-check-circle"></i> <?= esc(session()->getFlashdata('flash_msg')) ?></p>
        </div>
    <?php else: ?>
        <div class="alert alert-success">
            <p><i class="fas fa-check-circle"></i> Anda berhasil login ke sistem perpustakaan digital.</p>
        </div>
    <?php endif; ?>

    <div class="dashboard-stats">
        <div class="stat-card">
            <i class="fas fa-book-open"></i>
            <div class="number" id="stat-dipinjam"><?= esc($buku_dipinjam ?? 0) ?></div>
            <div class="label">Buku Dipinjam</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-history"></i>
            <div class="number" id="stat-riwayat"><?= esc($riwayat ?? 0) ?></div>
            <div class="label">Riwayat Peminjaman</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-calendar-check"></i>
            <div class="number" id="stat-aktif"><?= esc($aktif ?? 0) ?></div>
            <div class="label">Peminjaman Aktif</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-clock"></i>
            <div class="number" id="stat-tenggat"><?= esc($tenggat ?? 0) ?></div>
            <div class="label">Tenggat Waktu</div>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="card">
            <h3><i class="fas fa-book"></i> Daftar Peminjaman Buku</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-interaktif" id="peminjamanTable">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Judul Buku</th>
                            <th>Status</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($peminjaman)): ?>
                            <?php foreach ($peminjaman as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= esc($row['judul_buku']) ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'dipinjam'): ?>
                                            <span class="badge bg-warning text-dark">Dipinjam</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Dikembalikan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($row['tanggal_pinjam']) ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'dipinjam'): ?>
                                            <!-- Jika masih dipinjam, kosong -->
                                            -
                                        <?php else: ?>
                                            <?= esc($row['tanggal_kembali'] ?: '-') ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada buku yang dipinjam.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h3><i class="fas fa-bell"></i> Aktivitas Terbaru</h3>
            <ul class="recent-activities" id="recentActivities">
                <!-- Data akan diisi oleh JS -->
            </ul>
        </div>
    </div>

    <div class="dashboard-actions">
        <a href="<?= base_url('anggota/logout'); ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin logout?')">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<!-- JS & Plugin -->
<script>
    window.BASE_URL = "<?= base_url() ?>";
</script>
<script src="<?= base_url('assets/js/jquery-3.7.1.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?= base_url('assets/js/dashboard-anggota.js') ?>"></script>

<?= $this->include('template/anggota_footer'); ?>