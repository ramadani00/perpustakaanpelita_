<h2>Laporan Peminjaman & Pengembalian</h2>
<table border="1" cellpadding="8">
    <tr>
        <th>Anggota</th>
        <th>Buku</th>
        <th>Tanggal Pinjam</th>
        <th>Tanggal Kembali</th>
        <th>Status</th>
    </tr>
    <?php foreach($laporan as $row) { ?>
    <tr>
        <td><?php echo $row['id_anggota']; ?></td>
        <td><?php echo $row['id_buku']; ?></td>
        <td><?php echo $row['tanggal_pinjam']; ?></td>
        <td><?php echo $row['tanggal_kembali']; ?></td>
        <td><?php echo $row['status']; ?></td>
    </tr>
    <?php } ?>
</table>