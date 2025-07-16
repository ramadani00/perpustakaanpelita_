<!-- Include header admin dari template -->
<?= $this->include('template/admin_header'); ?>

<div id="container-tabel">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h2 class="m-0 font-weight-bold text-primary"><?= esc($title); ?></h2>
        </div>
        <div class="card-body">
            <form id="formAddPeminjaman" method="post" autocomplete="off">
                <?= csrf_field() ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="id_anggota" class="form-label">ID Anggota</label>
                            <input type="text" class="form-control" id="id_anggota" name="id_anggota" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="id_buku" class="form-label">ID Buku</label>
                            <input type="text" class="form-control" id="id_buku" name="id_buku" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="dipinjam">Dipinjam</option>
                                <option value="kembali">Kembali</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary me-md-2">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('admin/peminjaman/kelola_peminjaman') ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- JS & Plugin -->
<script>
    window.BASE_URL = "<?= base_url() ?>";
</script>
<script src="<?= base_url('assets/js/jquery-3.7.1.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?= base_url('peminjaman.js') ?>"></script>

<!-- Include footer admin dari template -->
<?= $this->include('template/admin_footer'); ?>