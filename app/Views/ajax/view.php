<!-- Include header admin dari template -->
<?= $this->include('template/admin_header'); ?>

<div class="container">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h2 class="m-0 font-weight-bold text-primary"><?= esc($title) ?></h2>
            <a href="<?= base_url('admin/ajax/admin_index') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="entry">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h1 class="mb-3"><?= esc($artikel['judul']) ?></h1>
                </div>
                <?php if ($artikel['gambar']): ?>
                <div class="col-md-6">
                    <img src="<?= base_url('gambar/' . esc($artikel['gambar'])) ?>" class="img-fluid rounded" alt="Gambar Artikel">
                </div>
                <?php endif; ?>
            </div>
            
            <div class="article">
                <?php
                // Gunakan Parsedown untuk mengkonversi Markdown ke HTML
                $parsedown = new \Parsedown();
                $parsedown->setSafeMode(true); // Aktifkan safe mode untuk keamanan
                echo $parsedown->text(esc($artikel['isi']));
                ?>
            </div>
            
            <div class="mt-4 pt-3 border-top">
                <a href="<?= base_url('admin/ajax/edit/' . $artikel['id']) ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Artikel
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Include footer admin dari template -->
<?= $this->include('template/admin_footer'); ?>