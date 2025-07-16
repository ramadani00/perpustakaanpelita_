<?php
namespace App\Controllers;

use App\Models\KategoriModel; 
use App\Models\BukuModel;

class Buku extends BaseController
{
    public function index()
    {
        $title = 'Daftar Buku'; 
        $model = new BukuModel(); 

        // Ambil parameter pencarian dari request
        $q = $this->request->getGet('q');
        $kategori_id = $this->request->getGet('kategori_id');

        $builder = $model->select('buku.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = buku.id_kategori', 'left');

        // Filter pencarian
        if ($q) {
            $builder->groupStart()
                ->like('buku.judul', $q)
                ->orLike('buku.penulis', $q)
                ->orLike('buku.penerbit', $q)
                ->orLike('buku.tahun_terbit', $q)
            ->groupEnd();
        }
        if ($kategori_id) {
            $builder->where('buku.id_kategori', $kategori_id);
        }

        $buku = $builder->orderBy('id_buku', 'ASC')->paginate(10);
        $pager = $model->pager; 

        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->findAll();
        // Ambil semua kategori untuk dropdown filter di view

        // Hitung total buku, anggota, dan artikel
        $total_buku = $model->countAll();
        $anggotaModel = new \App\Models\AnggotaModel();
        $total_anggota = $anggotaModel->countAll();
        $artikelModel = new \App\Models\ArtikelModel();
        $total_artikel = $artikelModel->countAll();

        // Memanggil view home dengan data buku, judul, dan pager
        return view('home', [
            'buku' => $buku,
            'title' => $title,
            'pager' => $pager,
            'kategori' => $kategori,
            'q' => $q ?? '',
            'kategori_id' => $kategori_id ?? '',
            'total_buku' => $total_buku,
            'total_anggota' => $total_anggota,
            'total_artikel' => $total_artikel,
        ]);
    }

    public function view($slug)
    {
        $model = new BukuModel();
        $buku = $model->where('slug', $slug)->first();

        if (!$buku) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Buku tidak ditemukan.');
        }

        // Parsedown (jika pakai markdown)
        $parsedown = new \Parsedown();
        $buku['isi_html'] = $parsedown->text($buku['isi']);

        // Ambil semua kategori untuk sidebar
        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->findAll();

        return view('buku/detail', [
            'buku' => $buku,
            'kategori' => $kategori, // <-- WAJIB dikirim ke view!
        ]);
    }

    public function detail($id)
    {
        $bukuModel = new BukuModel();
        $buku = $bukuModel
            ->select('buku.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = buku.id_kategori', 'left')
            ->where('buku.id_buku', $id)
            ->first();

        if (!$buku) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Buku tidak ditemukan');
        }

        return view('buku/detail', ['buku' => $buku]);
    }
}