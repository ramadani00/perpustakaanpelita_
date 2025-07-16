<?php
// Periksa apakah pengguna sudah login
$session = session();
if ($session->get('logged_in') === true) {
    // Jika sudah login, arahkan ke dashboard admin
    header("Location: " . base_url('/admin/ajax'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="<?= base_url('/style.css'); ?>">
</head>
<body class="login-centered">
    <div id="login-wrapper">
        <h1>Sign in</h1>
        <p>🔐 System administrators</p>
        <br>
        <br>
        <!-- Flash message untuk error -->
        <?php if (session()->getFlashdata('flash_msg')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('flash_msg') ?></div>
        <?php endif; ?>
        
        <form action="" method="post">
            <div class="mb-3">
                <label for="InputForEmail" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="InputForEmail" value="<?= old('email') ?>">
            </div>
            <div class="mb-3">
                <label for="InputForPassword" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="InputForPassword">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div style="margin-top: 20px;">
            <a href="<?= base_url('/'); ?>" class="btn btn-secondary">Kembali ke Home</a>
            <a href="<?= base_url('anggota/login'); ?>" class="btn btn-success" style="margin-left:10px;">Login Sebagai Anggota</a>
        </div>
    </div>
</body>
</html>