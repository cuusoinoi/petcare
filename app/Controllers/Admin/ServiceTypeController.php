<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\ServiceTypeModel;

class ServiceTypeController extends BaseController
{
    protected $serviceTypeModel;

    public function __construct()
    {
        $this->serviceTypeModel = new ServiceTypeModel();
    }

    /**
     * List all service types
     */
    public function index()
    {
        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $serviceTypes = $this->serviceTypeModel->orderBy('service_type_id', 'DESC')
                                               ->findAll($limit, $offset);
        $totalServiceTypes = $this->serviceTypeModel->countAllResults();
        $totalPages = ceil($totalServiceTypes / $limit);

        $data = [
            'title' => 'Danh sách dịch vụ - UIT Petcare',
            'serviceTypes' => $serviceTypes,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalServiceTypes' => $totalServiceTypes
        ];

        return view('admin/service_type/service_types', $data);
    }

    /**
     * Show add service type form
     */
    public function add()
    {
        $data = [
            'title' => 'Thêm dịch vụ - UIT Petcare'
        ];

        return view('admin/service_type/add_service_type', $data);
    }

    /**
     * Handle add service type
     */
    public function store()
    {
        $name = trim($this->request->getPost('name'));
        $description = trim($this->request->getPost('description')) ?: null;
        $price = (float)$this->request->getPost('price') ?: 0;

        if (empty($name)) {
            session()->setFlashdata('error', 'Vui lòng điền tên dịch vụ.');
            return redirect()->to('/admin/service-types/add');
        }

        $data = [
            'service_name' => $name,
            'description' => $description,
            'price' => $price
        ];

        if ($this->serviceTypeModel->insert($data)) {
            session()->setFlashdata('success', 'Thêm dịch vụ thành công!');
            return redirect()->to('/admin/service-types');
        } else {
            session()->setFlashdata('error', 'Không thể thêm dịch vụ. Vui lòng thử lại.');
            return redirect()->to('/admin/service-types/add');
        }
    }

    /**
     * Show edit service type form
     */
    public function edit($id)
    {
        $serviceType = $this->serviceTypeModel->find($id);

        if (!$serviceType) {
            session()->setFlashdata('error', 'Không tìm thấy dịch vụ!');
            return redirect()->to('/admin/service-types');
        }

        $data = [
            'title' => 'Chỉnh sửa dịch vụ - UIT Petcare',
            'serviceType' => $serviceType
        ];

        return view('admin/service_type/edit_service_type', $data);
    }

    /**
     * Handle update service type
     */
    public function update($id)
    {
        $serviceType = $this->serviceTypeModel->find($id);

        if (!$serviceType) {
            session()->setFlashdata('error', 'Không tìm thấy dịch vụ!');
            return redirect()->to('/admin/service-types');
        }

        $name = trim($this->request->getPost('name'));
        $description = trim($this->request->getPost('description')) ?: null;
        $price = (float)$this->request->getPost('price') ?: 0;

        if (empty($name)) {
            session()->setFlashdata('error', 'Vui lòng điền tên dịch vụ.');
            return redirect()->to('/admin/service-types/edit/' . $id);
        }

        $data = [
            'service_name' => $name,
            'description' => $description,
            'price' => $price
        ];

        if ($this->serviceTypeModel->update($id, $data)) {
            session()->setFlashdata('success', 'Cập nhật dịch vụ thành công!');
            return redirect()->to('/admin/service-types');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật dịch vụ. Vui lòng thử lại.');
            return redirect()->to('/admin/service-types/edit/' . $id);
        }
    }

    /**
     * Handle delete service type
     */
    public function delete($id)
    {
        $serviceType = $this->serviceTypeModel->find($id);

        if (!$serviceType) {
            session()->setFlashdata('error', 'Không tìm thấy dịch vụ!');
            return redirect()->to('/admin/service-types');
        }

        if ($this->serviceTypeModel->delete($id)) {
            session()->setFlashdata('success', 'Xóa dịch vụ thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa dịch vụ. Vui lòng thử lại.');
        }

        return redirect()->to('/admin/service-types');
    }
}
