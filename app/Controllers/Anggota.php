<?php

namespace App\Controllers;

use App\Models\AnggotaModel;

class Anggota extends BaseController
{
    public function index()
    {
        $title = 'Daftar Anggota';
        $model = new AnggotaModel();
        $anggota = $model->findAll();

        return view('anggota/index', compact('anggota', 'title'));
    }

    public function getLogin()
    {
        return view('anggota/login');
    }

    public function postLogin()
    {
        helper(['form']);

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            session()->setFlashdata("flash_msg", "Email dan Password wajib diisi.");
            return redirect()->to('/anggota/login')->withInput();
        }

        $session = session();
        $model = new AnggotaModel();

        $anggota = $model->where('email', $email)->first();

        if ($anggota && password_verify($password, $anggota['password'])) {
            // Session khusus anggota
            $session->set([
                'anggota_logged_in' => true,
                'anggota_id'        => $anggota['id'],
                'anggota_nama'      => $anggota['nama'],
                'anggota_email'     => $anggota['email'],
            ]);
            return redirect()->to('/anggota/dashboard');
        } else {
            session()->setFlashdata("flash_msg", "Email atau Password salah.");
            return redirect()->to('/anggota/login')->withInput();
        }
    }

    public function logout()
    {
        session()->remove(['anggota_logged_in', 'anggota_id', 'anggota_nama', 'anggota_email']);
        return redirect()->to('/anggota/login');
    }

    public function dashboard()
    {
        if (!session()->get('anggota_logged_in')) {
            return redirect()->to('/anggota/login');
        }

        $id_anggota = session()->get('anggota_id');
        $peminjamanModel = new \App\Models\PeminjamanModel();
        $bukuModel = new \App\Models\BukuModel();

        // Total buku dipinjam (status = dipinjam)
        $buku_dipinjam = $peminjamanModel
            ->where('id_anggota', $id_anggota)
            ->where('status', 'dipinjam')
            ->countAllResults();

        // Total riwayat peminjaman (semua status)
        $riwayat = $peminjamanModel
            ->where('id_anggota', $id_anggota)
            ->countAllResults();

        // Peminjaman aktif (status = dipinjam)
        $aktif = $buku_dipinjam;

        // Tenggat waktu (status = dipinjam dan tanggal_kembali < hari ini)
        $tenggat = $peminjamanModel
            ->where('id_anggota', $id_anggota)
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali <', date('Y-m-d'))
            ->countAllResults();

        // Ambil daftar peminjaman beserta judul buku
        $peminjaman = $peminjamanModel
            ->select('peminjaman.*, buku.judul as judul_buku')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku', 'left')
            ->where('peminjaman.id_anggota', $id_anggota)
            ->orderBy('peminjaman.tanggal_pinjam', 'DESC')
            ->findAll();

        return view('anggota/dashboard', [
            'buku_dipinjam' => $buku_dipinjam,
            'riwayat' => $riwayat,
            'aktif' => $aktif,
            'tenggat' => $tenggat,
            'peminjaman' => $peminjaman, // <-- penting!
        ]);
    }

    public function getAnggota($id = null)
    {
        $model = new AnggotaModel();
        $anggota = $model->find($id);

        if (!$anggota) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Anggota dengan ID $id tidak ditemukan.");
        }

        $data = [
            'title' => 'Detail Anggota',
            'anggota' => $anggota
        ];

        return view('anggota/detail', $data);
    }
}