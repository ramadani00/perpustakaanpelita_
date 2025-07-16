<?php
namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\KategoriModel;

class BukuAjaxController extends BaseController
{
    protected $model;
    protected $kategoriModel;

    public function __construct()
    {
        $this->model = new BukuModel();
        $this->kategoriModel = new KategoriModel();
        helper(['form', 'url']);
    }

    // Tampilkan halaman utama
    public function index()
    {
        $data = [
            'title' => 'Kelola Buku',
            'kategori' => $this->kategoriModel->findAll(),
        ];

        return view('buku/kelola_buku', $data);
    }

    public function kelola_buku()
    {
        $title = 'Kelola Buku';
        $model = new \App\Models\BukuModel();
        $kategoriModel = new \App\Models\KategoriModel();

        $data = [
            'title' => $title,
            'kategori' => $kategoriModel->findAll(),
        ];

        return view('buku/kelola_buku', $data);
    }

    // Ambil data buku untuk AJAX
    public function getData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'status' => 'error', 
                'message' => 'Method not allowed'
            ]);
        }

        // Parameter pencarian/sorting
        $q = $this->request->getGet('q');
        $kategori_id = $this->request->getGet('kategori_id');
        $sort = $this->request->getGet('sort') ?? 'id_buku';
        $order = $this->request->getGet('order') ?? 'asc';
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        // Query builder
        $builder = $this->model->select('buku.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = buku.id_kategori', 'left');

        // Filter pencarian
        if ($q) {
            $builder->groupStart()
                ->like('buku.judul', $q)
                ->orLike('buku.penulis', $q)
                ->orLike('buku.penerbit', $q)
                ->orLike('kategori.nama_kategori', $q)
                ->groupEnd();
        }

        // Filter kategori
        if ($kategori_id) {
            $builder->where('buku.id_kategori', $kategori_id);
        }

        // Sorting
        $builder->orderBy($sort, $order);

        // Pagination
        $data = $builder->paginate($perPage, 'default', $page);
        $pager = $this->model->pager;

        // Format data
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'id_buku' => $row['id_buku'],
                'judul' => $row['judul'],
                'id_kategori' => $row['id_kategori'],
                'nama_kategori' => $row['nama_kategori'],
                'penerbit' => $row['penerbit'],
                'tahun_terbit' => $row['tahun_terbit'],
                'stok' => $row['stok'],
                'penulis' => $row['penulis'],
                'gambar' => !empty($row['gambar']) ? base_url('uploads/' . $row['gambar']) : base_url('assets/img/no-image.png'),
            ];
        }

        return $this->response->setJSON([
            'data' => $result,
            'pagination' => $pager->links(),
            'csrf_test_name' => csrf_hash()
        ]);
    }

    // Tampilkan form tambah
    public function create()
    {
        $data = [
            'title' => "Tambah Buku",
            'kategori' => $this->kategoriModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('buku/form_add', $data);
    }

    // Proses tambah data
    public function store()
    {
        $rules = [
            'judul' => 'required|min_length[3]|max_length[255]',
            'penulis' => 'required|min_length[3]|max_length[255]',
            'penerbit' => 'required|min_length[3]|max_length[255]',
            'tahun_terbit' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to['.date('Y').']',
            'stok' => 'required|integer|greater_than_equal_to[0]',
            'id_kategori' => 'required|integer',
            'gambar' => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors(),
                    'csrf_test_name' => csrf_hash()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Upload gambar
        $file = $this->request->getFile('gambar');
        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads', $newName);

        // Simpan data
        $data = [
            'judul' => $this->request->getPost('judul'),
            'penulis' => $this->request->getPost('penulis'),
            'penerbit' => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'stok' => $this->request->getPost('stok'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'gambar' => $newName,
        ];

        $this->model->insert($data);
        $id_buku = $this->model->getInsertID();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Buku berhasil ditambahkan',
                'id_buku' => $id_buku,
                'csrf_test_name' => csrf_hash()
            ]);
        }

        return redirect()->to('/admin/buku/kelola_buku')->with('success', 'Buku berhasil ditambahkan');
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $buku = $this->model->find($id);
        if (!$buku) {
            return redirect()->back()->with('error', 'Buku tidak ditemukan');
        }

        $data = [
            'title' => "Edit Buku",
            'buku' => $buku,
            'kategori' => $this->kategoriModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('buku/form_edit', $data);
    }

    // Proses update data
    public function update($id)
    {
        $buku = $this->model->find($id);
        if (!$buku) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Buku tidak ditemukan'
                ]);
            }
            return redirect()->back()->with('error', 'Buku tidak ditemukan');
        }

        $rules = [
            'judul' => 'required|min_length[3]|max_length[255]',
            'penulis' => 'required|min_length[3]|max_length[255]',
            'penerbit' => 'required|min_length[3]|max_length[255]',
            'tahun_terbit' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to['.date('Y').']',
            'stok' => 'required|integer|greater_than_equal_to[0]',
            'id_kategori' => 'required|integer',
            'gambar' => 'permit_empty|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors(),
                    'csrf_test_name' => csrf_hash()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'judul' => $this->request->getPost('judul'),
            'penulis' => $this->request->getPost('penulis'),
            'penerbit' => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'stok' => $this->request->getPost('stok'),
            'id_kategori' => $this->request->getPost('id_kategori'),
        ];

        // Handle file upload
        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Hapus gambar lama
            if ($buku['gambar'] && file_exists(ROOTPATH . 'public/uploads/' . $buku['gambar'])) {
                unlink(ROOTPATH . 'public/uploads/' . $buku['gambar']);
            }
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $newName);
            $data['gambar'] = $newName;
        }

        $this->model->update($id, $data);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data berhasil diupdate',
                'id_buku' => $id,
                'csrf_test_name' => csrf_hash()
            ]);
        }

        return redirect()->to('/admin/buku/kelola_buku')->with('success', 'Buku berhasil diperbarui');
    }


    public function delete($id = null)
{
    // Check if this is an AJAX POST request
    if (!$this->request->isAJAX() || !$this->request->is('post')) {
        return $this->response->setStatusCode(405)->setJSON([
            'status' => 'error',
            'message' => 'Method not allowed',
            'csrf_test_name' => csrf_hash()
        ]);
    }

    // Get ID from POST if not in URL
    $id = $id ?? $this->request->getPost('id');
    
    if (!$id) {
        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'ID buku tidak ditemukan',
            'csrf_test_name' => csrf_hash()
        ]);
    }

    try {
        // Use the model's delete method
        $deleted = $this->model->delete($id);
        
        if ($deleted) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Buku berhasil dihapus',
                'csrf_test_name' => csrf_hash()
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Buku tidak ditemukan',
                'csrf_test_name' => csrf_hash()
            ]);
        }
    } catch (\Exception $e) {
        log_message('error', 'Gagal menghapus buku: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat menghapus buku',
            'csrf_test_name' => csrf_hash()
        ]);
    }
}
}