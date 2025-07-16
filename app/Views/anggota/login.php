<?php
$session = session();
if ($session->get('anggota_logged_in') === true) {
    header("Location: " . base_url('/anggota/dashboard'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Anggota</title>
    <link rel="stylesheet" href="<?= base_url('/style.css'); ?>">
</head>
<body class="login-centered">
    <div id="login-wrapper">
        <h1>Sign in</h1>
        <p>📚 Member Access</p>
        <br>
        <br>
        <?php if (session()->getFlashdata('flash_msg')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('flash_msg') ?></div>
        <?php endif; ?>
        <form action="<?= base_url('anggota/login'); ?>" method="post" autocomplete="on">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="InputForEmail" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="InputForEmail" value="<?= old('email') ?>" required autofocus>
            </div>
            <div class="mb-3">
                <label for="InputForPassword" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="InputForPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div style="margin-top: 20px;">
            <a href="<?= base_url('/'); ?>" class="btn btn-secondary">Kembali ke Home</a>
            <a href="<?= base_url('user/login'); ?>" class="btn btn-success" style="margin-left:10px;">Login Sebagai Admin</a>
        </div>
    </div>
</body>
</html>