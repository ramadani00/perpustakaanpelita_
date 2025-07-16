<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="cta-section" style="background-color: #10439F; padding: 40px; border-radius: 10px; text-align: center; color: white; margin-top: 50px;">
    <h2 style="font-size: 1.8rem; margin-bottom: 20px;">Mari Membangun Masyarakat Melek Literasi!</h2>
    <p style="font-size: 1.1rem; line-height: 1.7; margin-bottom: 25px; max-width: 800px; margin-left: auto; margin-right: auto;">
        Bergabunglah sebagai anggota Perpustakaan Pelita untuk mendapatkan akses ke ribuan buku berkualitas,
        fasilitas baca yang nyaman, dan berbagai program literasi menarik.
    </p>
    <a href="<?= base_url('anggota/login') ?>" class="btn" style="background-color: white; color: #10439F; padding: 12px 30px; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-block;">
        Daftar Sekarang
    </a>
</div>

<div class="content-container">

    <br>

<div class="about-section" style="display: flex; gap: 50px; align-items: center; margin-bottom: 60px;">
    <div class="about-text" style="flex: 1;">
        <h2 style="font-size: 1.8rem; color: #10439F; margin-bottom: 20px;">Profil</h2>
        <p style="font-size: 1.1rem; line-height: 1.7; color: #444;">
            Perpustakaan Pelita adalah pusat pengetahuan dan literasi yang berdiri sejak 1985 di jantung kota. 
            Kami hadir sebagai sumber inspirasi dan wawasan bagi masyarakat melalui koleksi buku berkualitas 
            dari berbagai disiplin ilmu.
        </p>
        <p style="font-size: 1.1rem; line-height: 1.7; color: #444; margin-top: 15px;">
            Dengan lebih dari 50.000 judul buku dan 100.000 anggota aktif, kami berkomitmen untuk:
        </p>
        <ul style="font-size: 1.1rem; line-height: 1.7; color: #444; padding-left: 20px;">
            <li style="margin-bottom: 10px;">Menyediakan akses terhadap literatur berkualitas</li>
            <li style="margin-bottom: 10px;">Mengembangkan minat baca masyarakat</li>
            <li style="margin-bottom: 10px;">Menjadi pusat kegiatan edukasi dan literasi</li>
            <li>Menyediakan fasilitas pembelajaran yang nyaman dan modern</li>
        </ul>
    </div>
    <div class="about-image" style="flex: 1; border-radius: 10px; overflow: hidden;">
        <img src="<?= base_url('gambar/2.jpg') ?>" alt="Interior Perpustakaan" style="width: 100%; height: auto; display: block;">
    </div>
</div>
</div>

<?= $this->endSection() ?>