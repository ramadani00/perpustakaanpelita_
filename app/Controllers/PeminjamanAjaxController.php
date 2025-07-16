<?php
namespace App\Controllers;

use App\Models\PeminjamanModel;
use CodeIgniter\Controller;

class PeminjamanAjaxController extends Controller
{

    // Ambil data peminjaman untuk DataTables/AJAX
    public function getData()
    {
        $model = new PeminjamanModel();

        $q = $this->request->getVar('q') ?? '';
        $status = $this->request->getVar('status') ?? '';
        $page = $this->request->getVar('page') ?? 1;
        $perPage = 10;

        // Ambil parameter sort dan order dari request
        $sort = $this->request->getVar('sort') ?? 'id';
        $order = strtolower($this->request->getVar('order') ?? 'asc');
        // Validasi kolom sort agar tidak bisa SQL injection
        $allowedSort = ['id', 'id_anggota', 'judul_buku', 'tanggal_pinjam', 'tanggal_kembali', 'status'];
        if (!in_array($sort, $allowedSort)) $sort = 'id';
        if (!in_array($order, ['asc', 'desc'])) $order = 'asc';

        $builder = $model->select('*');

        if (!empty($q)) {
            $builder->groupStart()
                ->like('judul_buku', $q)
                ->orLike('id_anggota', $q)
                ->orLike('id_buku', $q)
                ->groupEnd();
        }
        if (!empty($status)) {
            $builder->where('status', $status);
        }

        // Gunakan sort dan order dari request
        $data = $builder->orderBy($sort, $order)->paginate($perPage, 'default', $page);
        $pager = $model->pager;

        foreach ($data as &$row) {
            $row['tanggal_pinjam'] = ($row['tanggal_pinjam'] === '0000-00-00' || empty($row['tanggal_pinjam'])) ? '-' : $row['tanggal_pinjam'];
            $row['tanggal_kembali'] = ($row['tanggal_kembali'] === '0000-00-00' || empty($row['tanggal_kembali'])) ? '-' : $row['tanggal_kembali'];
        }

        $pagination = $pager->links('default', 'default_full');

        return $this->response->setJSON([
            'data' => $data,
            'pagination' => $pagination
        ]);
    }



    public function kelola_peminjaman()
    {
        $title = 'Daftar Peminjaman (Admin)';
        $model = new PeminjamanModel();

        // Optional: pencarian berdasarkan judul_buku atau status
        $q = $this->request->getVar('q') ?? '';
        $status = $this->request->getVar('status') ?? '';
        $page = $this->request->getVar('page') ?? 1;

        // Query builder untuk tabel peminjaman
        $builder = $model->select('*');

        if (!empty($q)) {
            $builder->groupStart()
                ->like('judul_buku', $q)
                ->orLike('id_anggota', $q)
                ->orLike('id_buku', $q)
                ->groupEnd();
        }

        if (!empty($status)) {
            $builder->where('status', $status);
        }

        $perPage = 10;
        $data = [
            'title' => $title,
            'q' => $q,
            'status' => $status,
            'peminjaman' => $builder->orderBy('id', 'DESC')->paginate($perPage, 'default', $page),
            'pager' => $model->pager
        ];

        // Jika request AJAX, kembalikan data JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['data' => $data['peminjaman']]);
        }

        return view('peminjaman/kelola_peminjaman', $data);
    }


    // Hapus peminjaman
    public function hapus($id = null)
    {
        $model = new PeminjamanModel();

        if (!$id || !$model->find($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ])->setStatusCode(404);
        }

        if (!$model->delete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus data'
            ])->setStatusCode(500);
        }

        return $this->response->setJSON(['success' => true]);
    }

    // Tambah data peminjaman (GET = tampilkan form, POST = proses tambah)
    public function add()
    {
        if ($this->request->getMethod() === 'post') {
            $model = new PeminjamanModel();
            $validation = \Config\Services::validation();

            $data = [
                'id_anggota'      => $this->request->getPost('id_anggota'),
                'id_buku'         => $this->request->getPost('id_buku'),
                'judul_buku'      => $this->request->getPost('judul_buku'),
                'tanggal_pinjam'  => $this->request->getPost('tanggal_pinjam'),
                'tanggal_kembali' => $this->request->getPost('tanggal_kembali'),
                'status'          => $this->request->getPost('status'),
            ];

            $rules = [
                'id_anggota'      => 'required',
                'id_buku'         => 'required',
                'judul_buku'      => 'required',
                'tanggal_pinjam'  => 'required|valid_date[Y-m-d]',
                'status'          => 'required|in_list[dipinjam,kembali]',
            ];

            if (!$validation->setRules($rules)->run($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validation->getErrors()
                ]);
            }

            if ($model->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data peminjaman berhasil ditambahkan'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menambah data peminjaman'
                ]);
            }
        }

        // Jika GET, tampilkan form
        return view('peminjaman/form_add', ['title' => 'Tambah Peminjaman']);
    }

    // Fungsi untuk mengembalikan buku
    public function kembalikan($id = null)
    {
        $model = new PeminjamanModel();

        // Cek apakah peminjaman ada dan status masih dipinjam
        $peminjaman = $model->where('id', $id)
                            ->where('status', 'dipinjam')
                            ->first();

        if (!$peminjaman) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan atau sudah dikembalikan'
            ]);
        }

        // Update data
        $data = [
            'status' => 'kembali',
            'tanggal_kembali' => date('Y-m-d') // Set tanggal kembali ke hari ini
        ];

        try {
            $model->update($id, $data);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Buku berhasil dikembalikan'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengembalikan buku: ' . $e->getMessage()
            ]);
        }
    }
}