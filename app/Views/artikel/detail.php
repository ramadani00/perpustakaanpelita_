<!-- Memperluas (extend) layout utama bernama 'main' -->
<?= $this->extend('layout/main') ?>

<!-- Memulai section 'content' untuk konten halaman ini -->
<?= $this->section('content') ?>

<div id="wrapper">
<!-- Bagian artikel -->
<article class="entry">
    <!-- Judul artikel, menggunakan helper esc() untuk mencegah XSS -->
    <h class="primary-text"><?= esc($artikel['judul']); ?></h>

    <!-- Gambar artikel, sumber di folder 'gambar', dengan nama file yang sudah di-escape untuk url -->
    <img src="<?= base_url('/gambar/' . esc($artikel['gambar'], 'url')); ?>" 
         alt="<?= esc($artikel['judul']); ?>" 
         class="img-fluid rounded shadow-sm">

    <!-- Isi artikel, tampilkan hasil parsing markdown -->
    <div class="text-justify"><?= $artikel['isi_html']; ?></div>
</article>
    <aside id="sidebar">
                <!-- Form filter kategori dengan metode GET -->
                <form action="" method="get">
                    <select name="kategori" onchange="this.form.submit()" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id_kategori']; ?>" <?= (request()->getGet('kategori') == $k['id_kategori']) ? 'selected' : ''; ?>>
                                <?= esc($k['nama_kategori']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>

                <!-- Menampilkan widget Artikel Terkini dengan category filter
                     Memanfaatkan View Cell dari CodeIgniter 4 -->
                <?= view_cell('App\Cells\ArtikelTerkini::render', ['kategori' => request()->getGet('kategori')]) ?>
    </aside>
</div>


<!-- JS & Plugin -->
<script>
    window.BASE_URL = "<?= base_url() ?>";
</script>
<script src="<?= base_url('assets/js/jquery-3.7.1.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?= base_url('app.js') ?>"></script>

<!-- Mengakhiri section 'content' -->
<?= $this->endSection() ?>
