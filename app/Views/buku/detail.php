<?php
$this->extend('layout/main');
?>

<?= $this->section('content'); ?>
    <div class="entry">
        <h2><?= esc($buku['judul']) ?></h2>
        <div style="display: flex; gap: 32px; align-items: flex-start; flex-wrap: wrap;">
            <div style="flex: 0 0 220px;">
                <?php
                    $imgPath = 'uploads/' . (!empty($buku['gambar']) ? $buku['gambar'] : 'default.png');
                ?>
                <img src="<?= base_url($imgPath) ?>" alt="<?= esc($buku['judul']) ?>" style="width:220px; height:320px; object-fit:cover; border-radius:10px; background:#f0f6ff;">
            </div>
            <div style="flex: 1 1 0;">
                <p><strong>Penulis:</strong> <?= esc($buku['penulis']) ?></p>
                <p><strong>Penerbit:</strong> <?= esc($buku['penerbit']) ?></p>
                <p><strong>Tahun Terbit:</strong> <?= esc($buku['tahun_terbit']) ?></p>
                <p><strong>Kategori:</strong> <?= esc($buku['nama_kategori'] ?? '-') ?></p>
                <p><strong>Stok:</strong> <?= esc($buku['stok']) ?></p>
            </div>
        </div>
        <a href="<?= base_url('/') ?>" class="btn btn-secondary" style="margin-top:32px;">&larr; Kembali ke Home</a>
    </div>
<?= $this->endSection(); ?>