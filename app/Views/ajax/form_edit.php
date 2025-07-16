<!-- Include header admin dari template -->
<?= $this->include('template/admin_header'); ?>

<div id="container-tabel">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h2 class="m-0 font-weight-bold text-primary"><?= esc($title); ?></h2>
        </div>
        <div class="card-body">
            <!-- Form untuk edit artikel -->
            <form id="<?= isset($artikel) ? 'editArtikelForm' : 'addArtikelForm' ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <!-- Token CSRF untuk keamanan form -->
                <?= csrf_field() ?>
                <?php if (isset($artikel)): ?>
                    <!-- Input hidden untuk id artikel -->
                    <input type="hidden" name="id" value="<?= esc($artikel['id']) ?>">
                <?php endif; ?>

                <!-- Input teks judul artikel -->
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                    <input type="text" name="judul" id="judul" class="form-control" 
                           value="<?= old('judul', $artikel['judul'] ?? '') ?>" 
                           placeholder="Masukkan judul artikel" required>
                    <div class="invalid-feedback" id="judul-error"></div>
                </div>

                <!-- Textarea untuk isi artikel -->
                <div class="mb-3">
                    <label for="isi" class="form-label">Isi Artikel <span class="text-danger">*</span></label>
                    <textarea name="isi" id="isi" rows="8" class="form-control" required><?= old('isi', $artikel['isi'] ?? '') ?></textarea>
                    <div class="invalid-feedback" id="isi-error"></div>
                </div>

                <!-- Input upload gambar -->
                <div class="mb-3">
                    <label for="gambar" class="form-label">Upload Gambar</label>
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                    <div class="invalid-feedback" id="gambar-error"></div>
                    <small class="text-muted">Format: JPG, JPEG, PNG (Max 2MB)</small>
                    <?php if (!empty($artikel['gambar'])): ?>
                        <div class="mt-2">
                            <img src="<?= base_url('gambar/' . esc($artikel['gambar'])) ?>" alt="Gambar" style="max-width:120px;">
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Dropdown pilih kategori artikel -->
                <div class="mb-3">
                    <label for="id_kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="id_kategori" id="id_kategori" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id_kategori']; ?>" 
                                <?= (old('id_kategori', $artikel['id_kategori'] ?? '') == $k['id_kategori'] ? 'selected' : '') ?>>
                                <?= esc($k['nama_kategori']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback" id="id_kategori-error"></div>
                </div>

                <!-- Tombol submit form -->
                <div class="d-flex">
                    <a href="<?= base_url('admin/ajax/admin_index') ?>" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary" style="width: 110px;">
                        <i class="fas fa-save"></i> <?= isset($artikel) ? 'Simpan' : 'Kirim' ?>
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
<script src="<?= base_url('app.js') ?>"></script>

<!-- Include footer admin dari template -->
<?= $this->include('template/admin_footer'); ?>