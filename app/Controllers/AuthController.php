<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Show login page
     */
    public function index()
    {
        // If already logged in, redirect to dashboard
        if (session()->has('username')) {
            return redirect()->to('/admin/dashboard');
        }

        $data = [
            'title' => 'Đăng nhập - UIT Petcare'
        ];

        return view('auth/login', $data);
    }

    /**
     * Handle login
     */
    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            session()->setFlashdata('error', 'Vui lòng nhập đầy đủ thông tin!');
            return redirect()->to('/admin');
        }

        $user = $this->userModel->authenticate($username, $password);

        if ($user) {
            session()->set([
                'username' => $user['username'],
                'fullname' => $user['fullname'],
                'role' => $user['role']
            ]);
            session()->setFlashdata('success', 'Đăng nhập thành công!');
            return redirect()->to('/admin/dashboard');
        } else {
            session()->setFlashdata('error', 'Sai tên đăng nhập hoặc mật khẩu!');
            return redirect()->to('/admin');
        }
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin');
    }
}
