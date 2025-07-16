<?php
namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table      = 'peminjaman';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_anggota', 'id_buku', 'judul_buku', 'tanggal_pinjam', 'tanggal_kembali', 'status'
    ];
}