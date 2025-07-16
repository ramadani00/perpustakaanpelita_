<div class="widget-box">
    <!-- Judul widget, menampilkan "Artikel Terkini" dan jika variabel $kategori ada, tambahkan nama kategori -->
    <h3 class="title">
        Berita Terkini
        <?php if (!empty($kategori) && isset($kategori_nama)): ?>
            - <?= esc($kategori_nama) ?>
        <?php endif; ?>
    </h3>

    <ul>
        <!-- Cek apakah ada data artikel -->
        <?php if (!empty($artikel)): ?>
            <!-- Loop setiap artikel dalam array $artikel -->
            <?php foreach ($artikel as $item): ?>
                <li>
                    <!-- Link ke halaman detail artikel berdasarkan slug -->
                    <a href="<?= site_url('artikel/' . $item['slug']) ?>">
                        <?= esc($item['judul']) ?>
                    </a>
                    <!-- Tampilkan tanggal terbit artikel dengan format tanggal "dd Mmm YYYY" -->
                    <p><?= date('d M Y', strtotime($item['tanggal'])) ?></p>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Jika tidak ada artikel, tampilkan pesan -->
            <p>Tidak ada artikel dalam kategori ini.</p>
        <?php endif; ?>
    </ul>

    <!-- Pagination -->
    <?php if (isset($pager)): ?>
        <?= $pager->simpleLinks('artikelterkini'); ?>
    <?php endif; ?>
</div>
