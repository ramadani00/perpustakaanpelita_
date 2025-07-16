<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>


<!-- Hero Container -->
<div class="hero-container" style="display:flex;align-items:center;justify-content:center;gap:48px;margin:40px 0 48px 0;">
    <div class="hero-text" style="max-width:800px;">
        <h1 style="font-size:2.3rem;font-weight:700;color:#10439F;line-height:1.2;margin-bottom:18px;">
            Selamat Datang<br> di Perpustakaan Pelita
        </h1>
        <p style="color:#444;font-size:1.08rem;line-height:1.7;margin-bottom:28px;">
            Temukan berbagai koleksi buku berkualitas dari berbagai genre dan kategori. Perpustakaan Pelita menyediakan akses mudah bagi pencinta ilmu pengetahuan dan literatur.
        </p>
        <div style="display:flex;gap:12px;margin-bottom:30px;">
            <a href="<?= base_url('anggota/login') ?>" class="btn btn-primary" style="background:#2563eb;color:#fff;padding:10px 22px;font-weight:600;border-radius:6px;text-decoration:none;">Login Dashboard</a>
        </div>
<div class="statistic-cards">
    <div class="stat-card">
        <div class="stat-icon minimalist-bg minimalist-bg-blue">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-number"><?= esc($total_buku ?? 0) ?></div>
        <div class="stat-label">Buku</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon minimalist-bg minimalist-bg-green">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-number"><?= esc($total_anggota ?? 0) ?></div>
        <div class="stat-label">Anggota</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon minimalist-bg minimalist-bg-orange">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="stat-number"><?= esc($total_artikel ?? 0) ?></div>
        <div class="stat-label">Artikel</div>
    </div>
</div>
    </div>
    <div class="hero-image" style="min-width:320px;max-width:600px;">
        <img src="<?= base_url('gambar/2.jpg') ?>"
             alt="Perpustakaan Pelita"
             style="width:100%;border-radius:14px;box-shadow:0 4px 24px rgba(0,0,0,0.08);object-fit:cover;">
    </div>
</div>
<!-- End Hero Container -->

<h2>Daftar Buku</h2>
<div class="buku-grid">
    <?php if (!empty($buku)): ?>
        <?php foreach ($buku as $b): ?>
            <a href="<?= base_url('buku/' . $b['id_buku']) ?>" class="buku-card" style="text-decoration:none;">
                <div class="buku-cover">
                    <?php
                        // Cek jika gambar ada, jika tidak pakai gambar default
                        $imgPath = 'uploads/' . ($b['gambar'] ? $b['gambar'] : 'default.png');
                    ?>
                    <img src="<?= base_url($imgPath) ?>" alt="<?= esc($b['judul']) ?>">
                </div>
                <div class="buku-info">
                    <h5><?= esc($b['judul']) ?></h5>
                    <p class="text-muted"><?= esc($b['penulis']) ?> | <?= esc($b['tahun_terbit']) ?></p>
                    <?php if (!empty($b['nama_kategori'])): ?>
                        <span class="kategori-badge"><?= esc($b['nama_kategori']) ?></span>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Tidak ada buku ditemukan.</p>
    <?php endif; ?>
</div>

<div class="cta-section" style="background-color: #10439F; padding: 40px; border-radius: 10px; text-align: center; color: white; margin-top: 50px;">
    <h2 style="font-size: 1.8rem; margin-bottom: 20px;">Mari Membangun Masyarakat Melek Literasi!</h2>
    <p style="font-size: 1.1rem; line-height: 1.7; margin-bottom: 25px; max-width: 800px; margin-left: auto; margin-right: auto;">
        Bergabunglah sebagai anggota Perpustakaan Pelita untuk mendapatkan akses ke ribuan buku berkualitas,
        fasilitas baca yang nyaman, dan berbagai program literasi menarik.
    </p>
</div>

<?= $this->endSection(); ?>