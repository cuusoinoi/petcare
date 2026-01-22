<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController as CIBaseController;

class BaseController extends CIBaseController
{
    /**
     * Constructor
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Check if user is logged in
        if (!session()->has('username')) {
            session()->setFlashdata('error', 'Vui lòng đăng nhập để tiếp tục!');
            redirect()->to('/admin')->send();
            exit;
        }

        // Kiểm tra role - chỉ admin và staff mới được vào
        $role = session()->get('role');
        if ($role !== 'admin' && $role !== 'staff') {
            session()->setFlashdata('error', 'Bạn không có quyền truy cập trang admin!');
            redirect()->to('/customer/dashboard')->send();
            exit;
        }
    }
}
