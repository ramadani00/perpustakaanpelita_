<!-- Include header admin dari template -->
<?= $this->include('template/admin_header'); ?>

<div id="container-tabel">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h2 class="m-0 font-weight-bold text-primary"><?= esc($title); ?></h2>
        </div>
        <div class="card-body">
            <!-- Form untuk edit buku -->
            <form id="editForm" method="post" enctype="multipart/form-data" action="<?= base_url('admin/buku/edit/' . $buku['id_buku']) ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id_buku" value="<?= esc($buku['id_buku']) ?>">

                <!-- Input teks judul buku -->
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku <span class="text-danger">*</span></label>
                    <input type="text" name="judul" id="judul" class="form-control" 
                           value="<?= old('judul', $buku['judul'] ?? '') ?>" 
                           placeholder="Masukkan judul buku" required>
                    <div class="invalid-feedback" id="judul-error"></div>
                </div>

                <!-- Input teks penulis -->
                <div class="mb-3">
                    <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                    <input type="text" name="penulis" id="penulis" class="form-control"
                           value="<?= old('penulis', $buku['penulis'] ?? '') ?>"
                           placeholder="Masukkan nama penulis" required>
                    <div class="invalid-feedback" id="penulis-error"></div>
                </div>

                <!-- Input teks penerbit -->
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit <span class="text-danger">*</span></label>
                    <input type="text" name="penerbit" id="penerbit" class="form-control"
                           value="<?= old('penerbit', $buku['penerbit'] ?? '') ?>"
                           placeholder="Masukkan nama penerbit" required>
                    <div class="invalid-feedback" id="penerbit-error"></div>
                </div>

                <!-- Input tahun terbit -->
                <div class="mb-3">
                    <label for="tahun_terbit" class="form-label">Tahun Terbit <span class="text-danger">*</span></label>
                    <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control"
                           value="<?= old('tahun_terbit', $buku['tahun_terbit'] ?? '') ?>"
                           placeholder="Masukkan tahun terbit" min="1900" max="<?= date('Y') ?>" required>
                    <div class="invalid-feedback" id="tahun_terbit-error"></div>
                </div>

                <!-- Input stok -->
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="stok" id="stok" class="form-control"
                           value="<?= old('stok', $buku['stok'] ?? '') ?>"
                           placeholder="Masukkan jumlah stok" min="0" required>
                    <div class="invalid-feedback" id="stok-error"></div>
                </div>

                <!-- Input upload gambar -->
                <div class="mb-3">
                    <label for="gambar" class="form-label">Upload Foto</label>
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                    <div class="invalid-feedback" id="gambar-error"></div>
                    <small class="text-muted">Format: JPG, JPEG, PNG (Max 2MB)</small>
                    <?php if (!empty($buku['gambar'])): ?>
                        <div class="mt-2">
                            <img src="<?= base_url('uploads/' . esc($buku['gambar'])) ?>" alt="Foto Buku" style="max-width:120px;">
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Dropdown pilih kategori buku -->
                <div class="mb-3">
                    <label for="id_kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="id_kategori" id="id_kategori" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id_kategori']; ?>" 
                                <?= (old('id_kategori', $buku['id_kategori'] ?? '') == $k['id_kategori'] ? 'selected' : '') ?>>
                                <?= esc($k['nama_kategori']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback" id="id_kategori-error"></div>
                </div>

                <!-- Tombol submit form -->
                <div class="d-flex">
                    <a href="<?= base_url('admin/buku') ?>" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary" style="width: 110px;">
                        <i class="fas fa-save"></i> <?= isset($buku) ? 'Simpan' : 'Kirim' ?>
                    </button>
                </div>
                
                <!-- Pesan status -->
                <div id="form-message" class="mt-3"></div>
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
<script src="<?= base_url('buku.js') ?>"></script>

<!-- Include footer admin dari template -->
<?= $this->include('template/admin_footer'); ?>