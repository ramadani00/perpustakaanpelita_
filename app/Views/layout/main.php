<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Set charset UTF-8 untuk encoding karakter -->
    <meta charset="UTF-8">

    <!-- Judul halaman, jika variabel $title ada, tampilkan dengan esc() untuk keamanan, jika tidak ada tampilkan 'Halaman Artikel' -->
    <title><?= isset($title) ? esc($title) : 'Perpustakaan Pelita' ?></title>

    <!-- Link stylesheet eksternal dari folder publik style.css -->
    <link rel="stylesheet" href="<?= base_url('style.css'); ?>">
    
</head>
<body>
    <div id="container">


        <!-- Navigasi utama -->
        <nav class="navbar">
            <div class="navbar-left">
                <!-- Link navigasi utama dengan base_url() agar fleksibel -->
                <a href="<?= base_url(); ?>">Beranda</a>
                <a href="<?= base_url('about'); ?>">Profil</a>
                <a href="<?= base_url('artikel'); ?>">Berita</a>
                <a href="<?= base_url('contact'); ?>">Kontak</a>
            </div>

            <div class="navbar-right">
                <!-- Jika user sudah login -->
                <?php if (session()->get('logged_in')): ?>
                    <!-- Tampilkan tombol Admin dan Logout -->
                    <a class="btn btn-outline-light" href="<?= base_url('admin/ajax'); ?>">Admin</a>
                    <a class="btn btn-outline-danger" href="<?= base_url('user/logout'); ?>">Logout</a>
                <?php elseif (session()->get('anggota_logged_in')): ?>
                    <!-- Jika anggota sudah login, tampilkan tombol Dashboard Anggota dan Logout -->
                    <a class="btn btn-outline-success ms-2" href="<?= base_url('anggota/dashboard'); ?>">Dashboard Anggota</a>
                    <a class="btn btn-outline-danger ms-2" href="<?= base_url('anggota/logout'); ?>">Logout</a>
                <?php else: ?>
                    <!-- Jika belum login, tampilkan tombol Admin dan Anggota yang mengarah ke halaman login masing-masing -->
                    <a class="btn btn-outline-light" href="<?= base_url('user/login'); ?>">Admin</a>
                    <a class="btn btn-outline-success ms-2" href="<?= base_url('anggota/login'); ?>">Anggota</a>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Wrapper utama konten dan sidebar -->
        <section id="wrapper">
            <section id="main">
                <!-- Render konten dinamis dari view yang extend layout ini -->
                <?= $this->renderSection('content'); ?>
            </section>
        </section>

        <!-- Footer website -->
        <footer>
            <p>&copy; 2025 - Universitas Pelita Bangsa</p>
        </footer>
    </div>
</body>
</html>

