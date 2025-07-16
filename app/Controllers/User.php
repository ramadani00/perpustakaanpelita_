<?php
namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    public function index() 
    {
        $title = 'Daftar User';
        $model = new UserModel();
        $users = $model->findAll();

        return view('user/index', compact('users', 'title'));
    }

    public function getLogin()
    {
        return view('user/login');
    }

    public function postLogin()
    {
        helper(['form']);

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            session()->setFlashdata("flash_msg", "Email dan Password wajib diisi.");
            return redirect()->to('/user/login')->withInput();
        }

        $session = session();
        $model = new UserModel();

        $user = $model->where('useremail', $email)->first();

        if ($user && password_verify($password, $user['userpassword'])) {
            // Session khusus admin/user
            $session->set([
                'logged_in' => true,
                'user_id'   => $user['id'],
                'user_name' => $user['username'],
                'user_email'=> $user['useremail'],
            ]);
            return redirect()->to('/admin/ajax');
        } else {
            session()->setFlashdata("flash_msg", "Email atau Password salah.");
            return redirect()->to('/user/login')->withInput();
        }
    }

    public function logout()
    {
        session()->destroy(); // Hapus semua session
        return redirect()->to('/user/login'); // Redirect ke view login admin
    }
}