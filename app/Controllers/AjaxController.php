<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\Response;
use App\Models\ArtikelModel;
use App\Models\KategoriModel;

class AjaxController extends Controller
{
    // Endpoint AJAX: mengembalikan data artikel dalam format JSON untuk datatable
    public function getData()
    {
        $request = service('request');
        $model = new \App\Models\ArtikelModel();

        $q = $request->getGet('q') ?? '';
        $kategori_id = $request->getGet('kategori_id') ?? '';
        $page = $request->getGet('page') ?? 1;
        $sort = $request->getGet('sort') ?? 'id';
        $order = strtolower($request->getGet('order') ?? 'asc');

        $allowedSortFields = ['id', 'judul', 'id_kategori', 'nama_kategori', 'status', 'tanggal', 'slug'];
        $sort = in_array($sort, $allowedSortFields) ? $sort : 'id';
        $order = in_array($order, ['asc', 'desc']) ? $order : 'asc';

        $builder = $model->select('artikel.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left');

        if ($q !== '') {
            $builder->like('artikel.judul', $q);
        }
        if ($kategori_id !== '') {
            $builder->where('artikel.id_kategori', $kategori_id);
        }

        // Sorting
        $sortField = ($sort === 'nama_kategori') ? 'kategori.nama_kategori' : 'artikel.' . $sort;
        $builder->orderBy($sortField, $order);

        $perPage = 10;
        $artikel = $builder->paginate($perPage, 'default', $page);
        $pager = $model->pager;

        return $this->response->setJSON([
            'data' => $artikel,
            'pagination' => $pager->links('default'),
            'csrf_test_name' => csrf_hash()
        ]);
    }


    public function index($kategoriSlug = null)
    {
        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        // Query builder dengan join kategori
        $builder = $model->select('artikel.*, kategori.nama_kategori')
                        ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
                        ->where('artikel.status', 'publish')
                        ->orderBy('artikel.tanggal', 'DESC');

        // Filter berdasarkan kategori jika ada
        if ($kategoriSlug) {
            $builder->where('kategori.slug_kategori', $kategoriSlug);
        }

        $data = [
            'title' => $kategoriSlug ? 'Artikel Kategori ' . ucfirst($kategoriSlug) : 'Daftar Artikel',
            'artikel' => $builder->paginate(5), // 5 item per halaman
            'pager' => $model->pager,
            'kategoriSlug' => $kategoriSlug
        ];

        // Jika request AJAX, kembalikan JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'artikel' => $data['artikel'],
                'pagination' => $data['pager']->links(),
                'kategoriSlug' => $kategoriSlug
            ]);
        }

        return view('artikel/index', $data);
    }

    // Tampilkan halaman admin artikel
    public function admin_index()
    {
        $title = 'Daftar Artikel (Admin)';
        $model = new ArtikelModel();

        $q = $this->request->getVar('q') ?? '';
        $kategori_id = $this->request->getVar('kategori_id') ?? '';
        $page = $this->request->getVar('page') ?? 1;

        $builder = $model->select('artikel.*, kategori.nama_kategori')
                        ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left');

        if ($q != '') {
            $builder->like('artikel.judul', $q);
        }
        if ($kategori_id != '') {
            $builder->where('artikel.id_kategori', $kategori_id);
        }

        $perPage = 7;
        $artikel = $builder->orderBy('tanggal', 'DESC')->paginate($perPage, 'default', $page);
        $pager = $model->pager;

        // If AJAX request, return JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'data' => $artikel,
                'pagination' => $pager->links(),
                'csrf_test_name' => csrf_hash()
            ]);
        }

        $data = [
            'title' => $title,
            'q' => $q,
            'kategori_id' => $kategori_id,
            'artikel' => $artikel,
            'pager' => $pager,
        ];

        $kategoriModel = new KategoriModel();
        $data['kategori'] = $kategoriModel->findAll();

        return view('ajax/admin_index', $data);
    }

    public function view($id)
    {
        $model = new ArtikelModel();
        $artikel = $model->find($id);
        
        if (!$artikel) {
            return redirect()->back()->with('error', 'Artikel tidak ditemukan');
        }

        $data = [
            'title' => "Detail Artikel",
            'artikel' => $artikel,
        ];

        return view('ajax/view', $data);
    }

    public function add()
    {
        // Cek jika request AJAX
        if ($this->request->isAJAX()) {
            $response = ['status' => 'error', 'message' => 'Invalid request'];
            
            // Validasi input
            $validation = \Config\Services::validation();
            $validation->setRules([
                'judul' => 'required|min_length[5]|max_length[255]',
                'isi' => 'required|min_length[10]',
                'id_kategori' => 'required|integer',
                'gambar' => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
            ]);

            if ($validation->withRequest($this->request)->run()) {
                try {
                    $file = $this->request->getFile('gambar');
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move(ROOTPATH . 'public/gambar', $newName);

                        $artikel = new ArtikelModel();
                        $artikel->insert([
                            'judul' => $this->request->getPost('judul'),
                            'isi' => $this->request->getPost('isi'),
                            'status' => 'publish',
                            'slug' => url_title($this->request->getPost('judul'), '-', true),
                            'gambar' => $newName,
                            'id_kategori' => $this->request->getPost('id_kategori'),
                            'tanggal' => date('Y-m-d H:i:s')
                        ]);

                        $response = [
                            'status' => 'success',
                            'message' => 'Artikel berhasil ditambahkan',
                            'data' => [
                                'id' => $artikel->getInsertID()
                            ],
                            'csrf_test_name' => csrf_hash() // Update CSRF token
                        ];
                    }
                } catch (\Exception $e) {
                    $response['message'] = 'Error: ' . $e->getMessage();
                }
            } else {
                $response['errors'] = $validation->getErrors();
                $response['message'] = 'Validasi gagal';
                $response['csrf_test_name'] = csrf_hash(); // Update CSRF token
            }

            return $this->response->setJSON($response);
        }

        // Jika bukan AJAX, tampilkan form biasa
        $kategoriModel = new KategoriModel();
        $data = [
            'title' => "Tambah Artikel",
            'kategori' => $kategoriModel->findAll(),
            'validation' => $validation ?? \Config\Services::validation(),
        ];

        return view('ajax/form_add', $data);
    }

    public function edit($id)
    {
        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();
        
        $artikel = $model->find($id);
        if (!$artikel) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['status' => 'error', 'message' => 'Artikel tidak ditemukan']);
            }
            return redirect()->back()->with('error', 'Artikel tidak ditemukan');
        }

        $data = [
            'title' => "Edit Artikel",
            'artikel' => $artikel,
            'kategori' => $kategoriModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('ajax/form_edit', $data);
    }

    public function update($id)
    {
        // Only allow AJAX requests
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)
                ->setJSON(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
        }

        $response = ['status' => 'error', 'message' => 'Invalid request'];
        $model = new ArtikelModel();
        
        // Validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required|min_length[5]|max_length[255]',
            'isi' => 'required|min_length[10]',
            'id_kategori' => 'required|integer',
            'gambar' => 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ]);

        if ($validation->withRequest($this->request)->run()) {
            try {
                $artikel = $model->find($id);
                if (!$artikel) {
                    return $this->response->setStatusCode(404)
                        ->setJSON(['status' => 'error', 'message' => 'Artikel tidak ditemukan']);
                }

                $data = [
                    'judul' => $this->request->getPost('judul'),
                    'isi' => $this->request->getPost('isi'),
                    'slug' => url_title($this->request->getPost('judul'), '-', true),
                    'id_kategori' => $this->request->getPost('id_kategori'),
                ];

                // Handle file upload
                $file = $this->request->getFile('gambar');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    // Delete old image if exists
                    if (!empty($artikel['gambar'])) {
                        $oldImage = ROOTPATH . 'public/gambar/' . $artikel['gambar'];
                        if (file_exists($oldImage)) {
                            unlink($oldImage);
                        }
                    }
                    
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'public/gambar', $newName);
                    $data['gambar'] = $newName;
                }

                $model->update($id, $data);

                $response = [
                    'status' => 'success',
                    'message' => 'Artikel berhasil diperbarui',
                    'data' => ['id' => $id],
                    'csrf_test_name' => csrf_hash()
                ];
            } catch (\Exception $e) {
                $response['message'] = 'Error: ' . $e->getMessage();
            }
        } else {
            $response['errors'] = $validation->getErrors();
            $response['message'] = 'Validasi gagal';
            $response['csrf_test_name'] = csrf_hash();
        }

        return $this->response->setJSON($response);
    }

    // Hapus artikel via AJAX POST
    public function delete($id)
    {
        // Pastikan hanya menerima request POST dan AJAX
        if (!$this->request->is('post') || !$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)
                ->setJSON(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
        }

        // CSRF otomatis diverifikasi oleh CodeIgniter jika CSRF diaktifkan di config

        $model = new ArtikelModel();
        $artikel = $model->find($id);

        if (!$artikel) {
            return $this->response->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Artikel tidak ditemukan.']);
        }

        // Hapus gambar jika ada
        if (!empty($artikel['gambar'])) {
            $gambarPath = ROOTPATH . 'public/gambar/' . $artikel['gambar'];
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
        }

        // Lakukan penghapusan
        if ($model->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Artikel berhasil dihapus.',
                'id' => $id
            ]);
        } else {
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Gagal menghapus artikel.']);
        }
    }

    public function dashboard_admin()
    {
        $bukuModel = new \App\Models\BukuModel();
        $peminjamanModel = new \App\Models\PeminjamanModel();
        $anggotaModel = new \App\Models\AnggotaModel();
        $artikelModel = new \App\Models\ArtikelModel();

        $total_buku = $bukuModel->countAllResults();
        $total_peminjaman = $peminjamanModel->where('status', 'dipinjam')->countAllResults();
        $total_dikembalikan = $peminjamanModel->where('status', 'kembali')->countAllResults();
        $total_transaksi = $peminjamanModel->countAllResults(); // Semua transaksi (dipinjam + kembali)
        $total_anggota = $anggotaModel->distinct()->countAllResults('email');
        $total_artikel = $artikelModel->countAllResults();

        return view('ajax/dashboard_admin', [
            'total_buku' => $total_buku,
            'total_peminjaman' => $total_peminjaman,
            'total_dikembalikan' => $total_dikembalikan,
            'total_transaksi' => $total_transaksi,
            'total_anggota' => $total_anggota,
            'total_artikel' => $total_artikel,
            'title' => 'Dashboard Admin'
        ]);
    }
}
?>

