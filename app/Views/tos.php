<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="content-container">
    <h1><?= esc($title); ?></h1>
    <hr><br>
    <p><?= nl2br(esc($content)); ?></p>
</div>

<?= $this->endSection() ?>