<?php
namespace App\Controllers;

use App\Models\PeminjamanModel;
use CodeIgniter\Controller;

class PeminjamanAjaxController extends Controller
{
    // Tampilkan halaman kelola peminjaman (view)
    public function index()
    {
        $model = new PeminjamanModel();
        $peminjaman = $model->orderBy('id', 'DESC')->findAll();
        return view('peminjaman/kelola_peminjaman', ['peminjaman' => $peminjaman]);
    }

}