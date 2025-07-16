<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\KategoriModel;
use CodeIgniter\Controller;

class Home extends BaseController
{
    public function index()
    {
        $bukuModel = new BukuModel();
        // Ambil data buku beserta nama kategori
        $buku = $bukuModel
            ->select('buku.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = buku.id_kategori', 'left')
            ->orderBy('buku.id_buku', 'DESC')
            ->findAll();

        // Ambil semua kategori untuk sidebar/layout
        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->findAll();

        // Pastikan akses database dengan benar
        $db = \Config\Database::connect();
        $total_buku = $db->table('buku')->countAll();
        $total_anggota = $db->table('anggota')->countAll();
        $total_artikel = $db->table('artikel')->countAll();

        return view('home', [
            'buku' => $buku,
            'kategori' => $kategori,
            'total_buku' => $total_buku,
            'total_anggota' => $total_anggota,
            'total_artikel' => $total_artikel
        ]);
    }
}

