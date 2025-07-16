<?php
namespace App\Controllers; 
// Mendefinisikan namespace controller, agar class ini dikenali sebagai controller di folder App\Controllers

use App\Models\ArtikelModel; 
// Memanggil model ArtikelModel untuk mengakses data artikel

use App\Models\KategoriModel; 
// Memanggil model KategoriModel untuk mengakses data kategori artikel

use Parsedown; 
// Memanggil library Parsedown untuk mengkonversi Markdown ke HTML

use CodeIgniter\Exceptions\PageNotFoundException; 
// Memanggil exception untuk halaman tidak ditemukan (404)

class Artikel extends BaseController
{
    public function index()
    {
        $title = 'Daftar Artikel'; 
        // Judul halaman yang akan ditampilkan di view

        $model = new ArtikelModel(); 
        // Instansiasi model ArtikelModel

        // Query: memilih artikel dengan join ke tabel kategori untuk mengambil nama kategori, diurutkan berdasarkan tanggal terbaru, dan dipaginasi 5 artikel per halaman
        $artikel = $model->select('artikel.*, kategori.nama_kategori')
                         ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
                         ->orderBy('tanggal', 'DESC')
                         ->paginate(8);

        $pager = $model->pager; 
        // Mengambil objek pagination untuk digunakan di view

        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->findAll();
        // Ambil semua kategori untuk dropdown filter di view

        // Memanggil view artikel/index dengan data artikel, judul, dan pager
        return view('artikel/index', compact('artikel', 'title', 'pager', 'kategori'));
    }

    public function view($slug)
    {
        $model = new ArtikelModel();
        $artikel = $model->where('slug', $slug)->first();

        if (!$artikel) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Artikel tidak ditemukan.');
        }

        // Parsedown (jika pakai markdown)
        $parsedown = new \Parsedown();
        $artikel['isi_html'] = $parsedown->text($artikel['isi']);

        // Ambil semua kategori untuk sidebar
        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->findAll();

        return view('artikel/detail', [
            'artikel' => $artikel,
            'kategori' => $kategori, // <-- WAJIB dikirim ke view!
        ]);
    }



    public function terkini()
    {
        $model = new ArtikelModel();
        // Query 20 artikel terbaru dengan join ke kategori untuk mengambil nama kategori
        $artikel = $model->select('artikel.*, kategori.nama_kategori')
                         ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
                         ->orderBy('tanggal', 'DESC')
                         ->findAll(20);

        $title = 'Artikel Terkini';

        // Tampilkan view artikel/terkini dengan data artikel dan judul
        return view('artikel/terkini', compact('artikel', 'title'));
    }
}
