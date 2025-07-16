<?php
namespace App\Models;
// Mendefinisikan namespace model ini berada di App\Models

use CodeIgniter\Model;
// Mengimpor class Model dari CodeIgniter sebagai kelas dasar untuk model ini

class ArtikelModel extends Model
// Mendeklarasikan kelas ArtikelModel yang mewarisi class Model dari CodeIgniter
{
    protected $table = 'artikel';
    // Menentukan nama tabel database yang digunakan adalah 'artikel'

    protected $primaryKey = 'id';
    // Menentukan primary key tabel adalah kolom 'id'

    protected $useAutoIncrement = true;
    // Mengaktifkan auto increment pada primary key

    protected $allowedFields = ['judul', 'isi', 'status', 'slug', 'gambar', 'tanggal', 'id_kategori'];
    // Menentukan kolom-kolom yang boleh diisi/diupdate melalui model ini (mass assignment protection)

    public function getArtikelDenganKategori()
    // Method untuk mengambil data artikel lengkap dengan nama kategori dari tabel kategori
    {
        return $this->db->table('artikel')
            // Mengakses tabel 'artikel' menggunakan query builder bawaan CodeIgniter

            ->select('artikel.*, kategori.nama_kategori')
            // Memilih semua kolom dari tabel artikel dan nama_kategori dari tabel kategori

            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            // Melakukan join tabel kategori dengan artikel berdasarkan id_kategori (left join)

            ->get()
            // Eksekusi query SELECT

            ->getResultArray();
            // Mengembalikan hasil query dalam bentuk array asosiatif
    }
}
