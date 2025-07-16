<?= $this->include('template/admin_header'); ?>

<div id="container-tabel">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h2 class="m-0 font-weight-bold text-primary"><?= esc($title); ?></h2>
        </div>
        <div class="card-body">
            <!-- Modal/Form Tambah Buku -->
            <form id="addForm" enctype="multipart/form-data" action="<?= base_url('admin/buku/add') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku <span class="text-danger">*</span></label>
                    <input type="text" name="judul" id="judul" class="form-control" required>
                    <div class="invalid-feedback" id="judul-error"></div>
                </div>
                <div class="mb-3">
                    <label for="penulis" class="form-label">Penulis</label>
                    <input type="text" name="penulis" id="penulis" class="form-control">
                    <div class="invalid-feedback" id="penulis-error"></div>
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" name="penerbit" id="penerbit" class="form-control">
                    <div class="invalid-feedback" id="penerbit-error"></div>
                </div>
                <div class="mb-3">
                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" min="1900" max="<?= date('Y') ?>">
                    <div class="invalid-feedback" id="tahun_terbit-error"></div>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="stok" id="stok" class="form-control" min="0" required>
                    <div class="invalid-feedback" id="stok-error"></div>
                </div>
                <div class="mb-3">
                    <label for="gambar" class="form-label">Upload Foto</label>
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                    <div class="invalid-feedback" id="gambar-error"></div>
                    <small class="text-muted">Format: JPG, JPEG, PNG (Max 2MB)</small>
                </div>
                <div class="mb-3">
                    <label for="id_kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="id_kategori" id="id_kategori" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <!-- Loop kategori dari backend -->
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id_kategori']; ?>"><?= esc($k['nama_kategori']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback" id="id_kategori-error"></div>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Buku</button>
            </form>
        </div>
    </div>
</div>


<script>
    window.BASE_URL = "<?= base_url() ?>";
</script>
<script src="<?= base_url('assets/js/jquery-3.7.1.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?= base_url('buku.js') ?>"></script>

<!-- Include footer admin dari template -->
<?= $this->include('template/admin_footer'); ?>