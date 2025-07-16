<?php
// app/Views/peminjaman/kelola_peminjaman.php
?>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="<?= base_url('/style.css');?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

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
                <p>Anda yakin ingin menghapus peminjaman ini?</p>
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
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="<?= base_url('admin/ajax') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>Dashboard
        </a>
    </div>
    <header class="dashboard-header mb-4 text-center">
        <div class="user-info d-inline-block">
          <h1>Kelola Peminjaman Buku</h1>
        </div>
    </header>
     <!-- Form pencarian artikel dan tombol tambah artikel -->
    <form id="search-form" class="row align-items-center mt-4 mb-4 g-2">
        <div class="col-lg-5 col-md-6 mb-2 mb-md-0">
            <input type="text" name="q" id="search-box"
                value="<?= isset($q) ? htmlspecialchars($q) : ''; ?>"
                placeholder="Cari judul buku"
                class="form-control"
                style="height: 48px;" /> 
        </div>
        <div class="col-1">
            <button type="button" id="clear-sort-btn" class="btn btn-outline-secondary d-flex align-items-center" title="Reset" style="height: 48px;">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
            </button>
        </div>
        <div class="col-1">
            <button type="submit" class="btn btn-primary d-flex align-items-center" style="height: 48px;">
                <i class="bi bi-search me-1"></i> Cari
            </button>
        </div>
         <div class="col-lg-5 col-md-4 text-lg-end mt-3 mt-lg-0">
            <a href="<?= base_url('/admin/peminjaman/add'); ?>" class="btn btn-success px-4 py-3" style="min-width:120px; font-size:1rem;">
                <i class="bi bi-plus-circle"></i> Tambah Peminjaman
            </a>
        </div>


        <?php if (session()->getFlashdata('flash_msg')): ?>
            <div class="col-12 mt-2">
                <div class="alert alert-success text-center mb-0 py-2">
                    <i class="bi bi-check-circle-fill"></i> <?= esc(session()->getFlashdata('flash_msg')) ?>
                </div>
            </div>
        <?php endif; ?>
    </form>
        <table id="peminjamanTable" class="table table-hover align-middle" style="table-layout: auto; width: 100%;">
            <thead class="table-primary text-center">
                <tr>
                    <th style="width:5%;">
                        <a href="#" class="sort-link" data-sort="id" data-order="asc">ID
                            <i class="bi bi-arrow-down-up sort-icon"></i>
                        </a>
                    </th>
                    <th style="width:15%;">
                        <a href="#" class="sort-link" data-sort="id_anggota" data-order="asc">ID Anggota
                            <i class="bi bi-arrow-down-up sort-icon"></i>
                        </a>
                    </th>
                    <th style="width:25%;">
                        <a href="#" class="sort-link" data-sort="judul_buku" data-order="asc">Judul Buku
                            <i class="bi bi-arrow-down-up sort-icon"></i>
                        </a>
                    </th>
                    <th style="width:15%;">
                        <a href="#" class="sort-link" data-sort="tanggal_pinjam" data-order="asc">Tanggal Pinjam
                            <i class="bi bi-arrow-down-up sort-icon"></i>
                        </a>
                    </th>
                    <th style="width:15%;">
                        <a href="#" class="sort-link" data-sort="tanggal_kembali" data-order="asc">Tanggal Kembali
                            <i class="bi bi-arrow-down-up sort-icon"></i>
                        </a>
                    </th>
                    <th style="width:10%;">
                        <a href="#" class="sort-link" data-sort="status" data-order="asc">Status
                            <i class="bi bi-arrow-down-up sort-icon"></i>
                        </a>
                    </th>
                    <th style="width:15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan diisi oleh AJAX -->
            </tbody>
        </table>
        <?php if (isset($pager)) : ?>
            <div class="mt-3 text-center">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript files -->
<script>
window.BUKU_AJAX_URL = "<?= base_url('admin/buku/get-data') ?>";
window.BASE_URL = "<?= base_url() ?>";
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="<?= base_url('peminjaman.js') ?>"></script>
<?= $this->include('template/admin_footer'); ?>