<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<section id="wrapper" style="background: #f8f9fa; min-height: 85vh; display: flex; justify-content: center;">
    <div style="width:100%;  display:flex; gap:32px; margin:0 auto; padding: 0 16px;">
        <div id="artikel-container" style="flex: 1 1 0; min-width: 0; padding: 40px 40px 30px 30px; background: #fff; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <h1 style="font-size:2.1rem; font-weight:700; color:#10439F; margin-bottom:28px; letter-spacing:1px;">Artikel & Berita Terbaru</h1>
            
            <?php if ($artikel && count($artikel) > 0): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
                    <?php foreach ($artikel as $row): ?>
                        <article class="entry artikel-item" style="padding: 20px; background:#fdfdfd; border:1.5px solid #e3f0ff; border-radius:12px; box-shadow:0 2px 8px rgba(96,181,255,0.06); display: flex; flex-direction: column; height: 100%;">
                            <?php if (!empty($row['gambar'])): ?>
                                <img src="<?= base_url('gambar/' . esc($row['gambar'])); ?>" 
                                     alt="<?= esc($row['judul']); ?>" 
                                     style="width: 100%; height: 160px; object-fit: cover; border-radius:8px; box-shadow:0 2px 8px rgba(96,181,255,0.08); margin-bottom: 16px;">
                            <?php endif; ?>
                            <div style="flex: 1; display: flex; flex-direction: column;">
                                <div style="margin-bottom:8px;">
                                    <span style="background:#60B5FF; color:#fff; font-size:0.85rem; font-weight:600; padding:3px 10px; border-radius:8px;">
                                        <?= esc($row['nama_kategori'] ?? 'Tidak ada kategori'); ?>
                                    </span>
                                </div>
                                <h2 style="font-size:1.15rem; font-weight:700; margin-bottom:12px; flex: 1;">
                                    <a href="<?= base_url('/artikel/' . esc($row['slug'])); ?>" style="color:#2563eb; text-decoration:none; transition:color 0.2s;">
                                        <?= esc($row['judul']); ?>
                                    </a>
                                </h2>
                                <p style="font-size:0.9rem; color:#444; margin-bottom:12px; line-height:1.5;"><?= substr(esc($row['isi']), 0, 100); ?>...</p>
                                <small class="text-muted" style="color:#888; margin-top: auto; font-size:0.8rem;">Diterbitkan pada: <?= date('d M Y', strtotime($row['tanggal'] ?? 'now')); ?></small>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div id="pagination-container" style="margin-top:32px;">
                    <?= $pager->links(); ?>
                </div>
            <?php else: ?>
                <article class="entry" style="padding:32px 24px; background:#fdfdfd; border-radius:12px; border:1.5px solid #e3f0ff;">
                    <h2 style="font-size:1.2rem; color:#10439F;">Belum ada artikel dalam kategori ini.</h2>
                </article>
            <?php endif; ?>
        </div>
        <aside id="sidebar" style="flex:0 0 340px; max-width:340px; padding:32px 24px 32px 32px; margin-top:40px; background:#f8f9fa; border-radius:14px; box-shadow:0 2px 8px rgba(96,181,255,0.06); position:relative;">
            <form action="" method="get" style="margin-bottom:32px;">
                <label for="kategori" style="font-weight:600; color:#10439F; margin-bottom:8px; display:block;">Filter Kategori</label>
                <select name="kategori" id="kategori" onchange="this.form.submit()" class="form-select" style="width:100%; padding:12px 16px; font-size:1rem; border-radius:10px; border:2px solid #ddd;">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id_kategori']; ?>" <?= (request()->getGet('kategori') == $k['id_kategori']) ? 'selected' : ''; ?>>
                            <?= esc($k['nama_kategori']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <div style="margin-top:10px;">
                <?= view_cell('App\Cells\ArtikelTerkini::render', ['kategori' => request()->getGet('kategori')]) ?>
            </div>
        </aside>
    </div>
</section>
<?= $this->endSection() ?>