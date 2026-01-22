<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\MedicineModel;

class MedicineController extends BaseController
{
    protected $medicineModel;

    public function __construct()
    {
        $this->medicineModel = new MedicineModel();
    }

    public function index()
    {
        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $medicines = $this->medicineModel->getMedicinesPaginated($limit, $offset);
        $totalMedicines = $this->medicineModel->countAllResults();
        $totalPages = ceil($totalMedicines / $limit);

        $data = [
            'title' => 'Danh sách thuốc - UIT Petcare',
            'medicines' => $medicines,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalMedicines' => $totalMedicines
        ];

        return view('admin/medicine/medicines', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Thêm thuốc - UIT Petcare'
        ];
        return view('admin/medicine/add_medicine', $data);
    }

    public function store()
    {
        $name = trim($this->request->getPost('name'));
        $route = trim($this->request->getPost('route'));

        if (empty($name)) {
            session()->setFlashdata('error', 'Vui lòng nhập tên thuốc.');
            return redirect()->to('/admin/medicines/add');
        }

        $data = [
            'medicine_name' => $name,
            'medicine_route' => $route
        ];

        if ($this->medicineModel->insert($data)) {
            session()->setFlashdata('success', 'Thêm thuốc thành công!');
            return redirect()->to('/admin/medicines');
        } else {
            session()->setFlashdata('error', 'Không thể thêm thuốc. Vui lòng thử lại.');
            return redirect()->to('/admin/medicines/add');
        }
    }

    public function edit($id)
    {
        $medicine = $this->medicineModel->find($id);

        if (!$medicine) {
            session()->setFlashdata('error', 'Không tìm thấy thuốc!');
            return redirect()->to('/admin/medicines');
        }

        $data = [
            'title' => 'Chỉnh sửa thuốc - UIT Petcare',
            'medicine' => $medicine
        ];

        return view('admin/medicine/edit_medicine', $data);
    }

    public function update($id)
    {
        $medicine = $this->medicineModel->find($id);

        if (!$medicine) {
            session()->setFlashdata('error', 'Không tìm thấy thuốc!');
            return redirect()->to('/admin/medicines');
        }

        $name = trim($this->request->getPost('name'));
        $route = trim($this->request->getPost('route'));

        if (empty($name)) {
            session()->setFlashdata('error', 'Vui lòng nhập tên thuốc.');
            return redirect()->to('/admin/medicines/edit/' . $id);
        }

        $data = [
            'medicine_name' => $name,
            'medicine_route' => $route
        ];

        if ($this->medicineModel->update($id, $data)) {
            session()->setFlashdata('success', 'Cập nhật thuốc thành công!');
            return redirect()->to('/admin/medicines');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật thuốc. Vui lòng thử lại.');
            return redirect()->to('/admin/medicines/edit/' . $id);
        }
    }

    public function delete($id)
    {
        $medicine = $this->medicineModel->find($id);

        if (!$medicine) {
            session()->setFlashdata('error', 'Không tìm thấy thuốc!');
            return redirect()->to('/admin/medicines');
        }

        if ($this->medicineModel->delete($id)) {
            session()->setFlashdata('success', 'Xóa thuốc thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa thuốc. Vui lòng thử lại.');
        }

        return redirect()->to('/admin/medicines');
    }
}
