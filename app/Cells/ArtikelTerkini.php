<?php
namespace App\Cells;
// Mendefinisikan namespace kelas ini berada di App\Cells

use App\Models\ArtikelModel;
// Mengimpor model ArtikelModel untuk akses data artikel
use App\Models\KategoriModel;
// Mengimpor model KategoriModel untuk akses data kategori

class ArtikelTerkini
// Mendeklarasikan kelas ArtikelTerkini sebagai Cell (komponen kecil untuk view)
{
    public function render($kategori = null)
    // Method utama untuk merender artikel terkini, opsional bisa filter berdasarkan kategori
    {
        $model = new ArtikelModel();
        // Membuat instance model ArtikelModel

        $perPage = 10;
        $page = isset($_GET['page_artikelterkini']) ? (int)$_GET['page_artikelterkini'] : 1;
        // Mengatur jumlah artikel per halaman dan halaman yang sedang diakses dari query string

        $query = $model->orderBy('tanggal', 'DESC');
        // Mempersiapkan query untuk mengambil artikel terbaru berdasarkan tanggal terbaru

        // Ambil nama kategori jika filter kategori digunakan
        $kategori_nama = null;
        if ($kategori) {
            $query->where('id_kategori', $kategori);
            // Jika parameter kategori diberikan, batasi query berdasarkan kategori tersebut

            // Ambil nama kategori dari tabel kategori
            $kategoriModel = new KategoriModel();
            $kategoriData = $kategoriModel->find($kategori);
            if ($kategoriData) {
                $kategori_nama = $kategoriData['nama_kategori'];
                // Jika data kategori ditemukan, ambil nama kategori
            }
        }

        $artikel = $query->paginate($perPage, 'artikelterkini', $page);
        $pager = $model->pager;
        // Eksekusi query dengan paginasi dan ambil data artikel sesuai kriteria
        // Siapkan juga objek pager untuk navigasi halaman

        return view('components/artikel_terkini', [
            'artikel' => $artikel,
            'kategori' => $kategori,
            'kategori_nama' => $kategori_nama,
            'pager' => $pager
        ]);
        // Mengembalikan hasil render view 'components/artikel_terkini' dengan data artikel, kategori, dan pager
    }
}
