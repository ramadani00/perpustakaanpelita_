<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Header Template -->
<?= $this->include('template/admin_header'); ?>

<!-- Loading Overlay -->
<div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; backdrop-filter: blur(3px);">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="text-center text-white">
            <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="mt-3">Memuat data...</h5>
            <p class="text-muted">Harap tunggu sebentar</p>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data buku ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<div id="container-form-search">
    <!-- Tombol kembali ke dashboard admin -->
    <div class="mb-3">
        <a href="<?= base_url('admin/ajax') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>Dashboard
        </a>
    </div>
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
    
    <!-- Form pencarian buku dan tombol tambah buku -->
    <form id="search-form" class="form-search row align-items-center mt-lg-4 mb-3" autocomplete="off">
        <input type="hidden" name="csrf_test_name" value="<?= csrf_hash() ?>">
        <div class="col-lg-3 col-md-1 col-12">
            <input type="text" name="q" id="search-box" value="" placeholder="Cari data" class="form-control" />
        </div>
        <div class="col-lg-3 col-md-4 col-12">
            <select name="kategori_id" id="category-filter" class="form-select">
                <option value="">Semua Kategori</option>
                <?php if (!empty($kategori)): ?>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= esc($k['id_kategori']); ?>">
                            <?= esc($k['nama_kategori']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-lg-1 col-md-4 col-6">
            <button type="button" id="clear-sort-btn" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center shadow-sm ms-2" title="Clear Sorting">
                <i class="bi bi-arrow-counterclockwise me-2"></i> Reset
            </button>
        </div>
        <div class="col-lg-1 col-md-4 col-6">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Cari
            </button>
        </div>
        <div class="col-lg-3 col-md-4 text-lg-end mt-3 mt-lg-0">
            <a href="<?= base_url('/admin/buku/add'); ?>" class="btn btn-success px-4 py-3" style="min-width:120px; font-size:1rem;">
                <i class="bi bi-plus-circle"></i> Tambah Buku
            </a>
        </div>
    </form>
</div> 

<div id="container-tabel">
    <!-- Tabel data buku -->
    <table id="bukuTable" class="table table-hover align-middle" style="table-layout: auto; width: 100%;">
        <thead class="table-primary text-center">
            <tr>
                <th style="width: 5%;">
                    <a href="#" class="sort-link" data-sort="id_buku" data-order="asc">ID 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 20%;">
                    <a href="#" class="sort-link" data-sort="judul" data-order="asc">Judul 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 10%;">
                    <a href="#" class="sort-link" data-sort="id_kategori" data-order="asc">ID Kategori 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 15%;">
                    <a href="#" class="sort-link" data-sort="nama_kategori" data-order="asc">Kategori 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 12%;">
                    <a href="#" class="sort-link" data-sort="penerbit" data-order="asc">Penerbit 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 8%;">
                    <a href="#" class="sort-link" data-sort="tahun_terbit" data-order="asc">Tahun 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 8%;">
                    <a href="#" class="sort-link" data-sort="stok" data-order="asc">Stok 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 12%;">
                    <a href="#" class="sort-link" data-sort="penulis" data-order="asc">Penulis 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 10%;">
                    Gambar
                </th>
                <th style="width: 15%;">
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan diisi oleh AJAX -->
            <tr>
                <td colspan="10" class="text-center py-4">
                    <i class="bi bi-exclamation-circle me-2"></i>Memuat data buku...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Pagination AJAX akan diisi oleh JS -->

<!-- JavaScript files -->
<script>
window.BUKU_AJAX_URL = "<?= base_url('admin/buku/get-data') ?>";
window.BASE_URL = "<?= base_url() ?>";
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="<?= base_url('buku.js') ?>"></script>

<!-- Footer Template -->
<?= $this->include('template/admin_footer'); ?>