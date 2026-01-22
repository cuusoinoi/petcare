<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->has('username')) {
            session()->setFlashdata('error', 'Vui lòng đăng nhập để tiếp tục!');
            return redirect()->to('/admin');
        }

        // Kiểm tra role - chỉ admin và staff mới được vào
        $role = session()->get('role');
        if ($role !== 'admin' && $role !== 'staff') {
            session()->setFlashdata('error', 'Bạn không có quyền truy cập trang này!');
            return redirect()->to('/customer/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
