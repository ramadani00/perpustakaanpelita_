<?php
namespace App\Controllers;
use App\Models\PeminjamanModel;

class Laporan extends BaseController
{
    public function index()
    {
        $model = new PeminjamanModel();
        $data['laporan'] = $model->findAll();
        return view('laporan/index', $data);
    }
}