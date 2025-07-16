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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menghapus artikel ini?</p>
                <p class="text-muted"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Batal</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger"><i class="bi bi-trash-fill"></i> Hapus</button>
            </div>
        </div>
    </div>
</div>

<div id="container-form-search">
        <!-- Tombol kembali ke dashboard admin -->
    <div class="mb-3">
        <a href="<?= base_url('admin/ajax') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Dashboard
        </a>
    </div>

    <header class="dashboard-header">
        <div class="user-info">
            <h1>Kelola Artikel</h1>
        </div>
    </header>
    

    <!-- Form pencarian artikel dan tombol tambah artikel -->
    <form id="search-form" class="form-search row align-items-center mt-lg-4 mb-3">
        <div class="col-lg-3 col-md-1 col-12">
            <input type="text" name="q" id="search-box" value="<?= isset($q) ? htmlspecialchars($q) : ''; ?>" placeholder="Cari data" class="form-control" />
        </div>
        <div class="col-lg-3 col-md-4 col-12">
            <select name="kategori_id" id="category-filter" class="form-select">
                <option value="">Semua Kategori</option>
                <?php if (!empty($kategori)): ?>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id_kategori']; ?>" <?= (isset($kategori_id) && $kategori_id == $k['id_kategori']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($k['nama_kategori']); ?>
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
            <a href="<?= base_url('/admin/ajax/add'); ?>" class="btn btn-success px-4 py-3" style="min-width:120px; font-size:1rem;">
                <i class="bi bi-plus-circle"></i> Tambah Artikel
            </a>
        </div>
    </form>
</div> 
               
<div id="container-tabel">
    <!-- Tabel data artikel -->
    <table id="artikelTable" class="table table-hover align-middle" style="table-layout: auto; width: 100%;">
        <thead class="table-primary text-center">
            <tr>
                <th style="width: 5%;">
                    <a href="#" class="sort-link" data-sort="id" data-order="asc">ID 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 40%;">
                    <a href="#" class="sort-link" data-sort="judul" data-order="asc">Judul & Isi 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 10%;">
                    <a href="#" class="sort-link" data-sort="id_kategori" data-order="asc">ID Kategori 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 10%;">
                    <a href="#" class="sort-link" data-sort="nama_kategori" data-order="asc">Kategori 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 10%;">
                    <a href="#" class="sort-link" data-sort="tanggal" data-order="asc">Tanggal 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 5%;">
                    <a href="#" class="sort-link" data-sort="status" data-order="asc">Status 
                        <i class="bi bi-arrow-down-up sort-icon"></i>
                    </a>
                </th>
                <th style="width: 20%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan diisi oleh AJAX -->
            <tr>
                <td colspan="7" class="text-center">Memuat data...</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Pagination AJAX -->
<div id="pagination" class="mt-4"></div>

<!-- JS & Plugin -->
<script>
    window.BASE_URL = "<?= base_url() ?>";
</script>


<script src="<?= base_url('app.js') ?>"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script><script src="<?= base_url('assets/js/jquery-3.7.1.js') ?>"></script><script src="<?= base_url('assets/js/jquery-3.7.1.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?= base_url('app.js') ?>"></script>
<!-- Footer Template -->
<?= $this->include('template/admin_footer'); ?>