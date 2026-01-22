<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\VaccineModel;

class VaccineController extends BaseController
{
    protected $vaccineModel;

    public function __construct()
    {
        $this->vaccineModel = new VaccineModel();
    }

    public function index()
    {
        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $vaccines = $this->vaccineModel->getVaccinesPaginated($limit, $offset);
        $totalVaccines = $this->vaccineModel->countAllResults();
        $totalPages = ceil($totalVaccines / $limit);

        $data = [
            'title' => 'Danh sách vaccine - UIT Petcare',
            'vaccines' => $vaccines,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalVaccines' => $totalVaccines
        ];

        return view('admin/vaccine/vaccines', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Thêm vaccine - UIT Petcare'
        ];
        return view('admin/vaccine/add_vaccine', $data);
    }

    public function store()
    {
        $name = trim($this->request->getPost('name'));
        $description = trim($this->request->getPost('description'));

        if (empty($name)) {
            session()->setFlashdata('error', 'Vui lòng nhập tên vaccine.');
            return redirect()->to('/admin/vaccines/add');
        }

        $data = [
            'vaccine_name' => $name,
            'description' => $description
        ];

        if ($this->vaccineModel->insert($data)) {
            session()->setFlashdata('success', 'Thêm vaccine thành công!');
            return redirect()->to('/admin/vaccines');
        } else {
            session()->setFlashdata('error', 'Không thể thêm vaccine. Vui lòng thử lại.');
            return redirect()->to('/admin/vaccines/add');
        }
    }

    public function edit($id)
    {
        $vaccine = $this->vaccineModel->find($id);

        if (!$vaccine) {
            session()->setFlashdata('error', 'Không tìm thấy vaccine!');
            return redirect()->to('/admin/vaccines');
        }

        $data = [
            'title' => 'Chỉnh sửa vaccine - UIT Petcare',
            'vaccine' => $vaccine
        ];

        return view('admin/vaccine/edit_vaccine', $data);
    }

    public function update($id)
    {
        $vaccine = $this->vaccineModel->find($id);

        if (!$vaccine) {
            session()->setFlashdata('error', 'Không tìm thấy vaccine!');
            return redirect()->to('/admin/vaccines');
        }

        $name = trim($this->request->getPost('name'));
        $description = trim($this->request->getPost('description'));

        if (empty($name)) {
            session()->setFlashdata('error', 'Vui lòng nhập tên vaccine.');
            return redirect()->to('/admin/vaccines/edit/' . $id);
        }

        $data = [
            'vaccine_name' => $name,
            'description' => $description
        ];

        if ($this->vaccineModel->update($id, $data)) {
            session()->setFlashdata('success', 'Cập nhật vaccine thành công!');
            return redirect()->to('/admin/vaccines');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật vaccine. Vui lòng thử lại.');
            return redirect()->to('/admin/vaccines/edit/' . $id);
        }
    }

    public function delete($id)
    {
        $vaccine = $this->vaccineModel->find($id);

        if (!$vaccine) {
            session()->setFlashdata('error', 'Không tìm thấy vaccine!');
            return redirect()->to('/admin/vaccines');
        }

        if ($this->vaccineModel->delete($id)) {
            session()->setFlashdata('success', 'Xóa vaccine thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa vaccine. Vui lòng thử lại.');
        }

        return redirect()->to('/admin/vaccines');
    }
}
