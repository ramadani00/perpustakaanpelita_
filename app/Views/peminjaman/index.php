<?php
// app/Views/peminjaman/index.php
?>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<!-- Toastr CSS -->
<link rel="stylesheet" href="<?= base_url('/style.css');?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">


<div class="container">
    <h2>Daftar Peminjaman</h2>
    <a href="<?= base_url('anggota/peminjaman/tambah') ?>" class="btn btn-primary mb-3">Tambah Peminjaman</a>
    <div class="table-responsive">
        <table id="peminjamanTable" class="table table-striped table-hover" border="1" cellpadding="5">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Judul Buku</th>
                    <th>Status</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan diisi oleh JS -->
            </tbody>
        </table>
    </div>
    <script src="<?= base_url('assets/js/peminjaman.js') ?>"></script>
</div>

<!-- JS & Plugin -->
<script>
    window.BASE_URL = "<?= base_url() ?>";
</script>
<script src="<?= base_url('assets/js/jquery-3.7.1.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?= base_url('app.js') ?>"></script>
<!-- Footer Template -->
<?= $this->include('template/admin_footer'); ?>