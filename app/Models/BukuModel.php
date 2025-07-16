<?php
namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['judul', 'id_kategori', 'penerbit', 'tahun_terbit', 'stok', 'penulis', 'gambar'];
    protected $returnType = 'array';

    public function getBukuDenganKategori()
    {
        return $this->select('buku.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = buku.id_kategori', 'left')
            ->findAll();
    }
}