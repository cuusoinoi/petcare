<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\DoctorModel;

class DoctorController extends BaseController
{
    protected $doctorModel;

    public function __construct()
    {
        $this->doctorModel = new DoctorModel();
    }

    /**
     * List all doctors
     */
    public function index()
    {
        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $doctors = $this->doctorModel->getDoctorsPaginated($limit, $offset);
        $totalDoctors = $this->doctorModel->getDoctorCount();
        $totalPages = ceil($totalDoctors / $limit);

        $data = [
            'title' => 'Danh sách bác sĩ - UIT Petcare',
            'doctors' => $doctors,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalDoctors' => $totalDoctors
        ];

        return view('admin/doctor/doctors', $data);
    }

    /**
     * Show add doctor form
     */
    public function add()
    {
        $data = [
            'title' => 'Thêm bác sĩ - UIT Petcare'
        ];

        return view('admin/doctor/add_doctor', $data);
    }

    /**
     * Handle add doctor
     */
    public function store()
    {
        $fullname = trim($this->request->getPost('fullname'));
        $phone = trim($this->request->getPost('phone'));
        $identity_card = trim($this->request->getPost('identity_card')) ?: null;
        $address = trim($this->request->getPost('address'));
        $note = trim($this->request->getPost('note')) ?: null;

        if (empty($fullname) || empty($phone) || empty($address)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/doctors/add');
        }

        $data = [
            'doctor_name' => $fullname,
            'doctor_phone_number' => $phone,
            'doctor_identity_card' => $identity_card,
            'doctor_address' => $address,
            'doctor_note' => $note
        ];

        if ($this->doctorModel->insert($data)) {
            session()->setFlashdata('success', 'Thêm bác sĩ thành công!');
            return redirect()->to('/admin/doctors');
        } else {
            session()->setFlashdata('error', 'Không thể thêm bác sĩ. Vui lòng thử lại.');
            return redirect()->to('/admin/doctors/add');
        }
    }

    /**
     * Show edit doctor form
     */
    public function edit($id)
    {
        $doctor = $this->doctorModel->find($id);

        if (!$doctor) {
            session()->setFlashdata('error', 'Không tìm thấy bác sĩ!');
            return redirect()->to('/admin/doctors');
        }

        $data = [
            'title' => 'Chỉnh sửa bác sĩ - UIT Petcare',
            'doctor' => $doctor
        ];

        return view('admin/doctor/edit_doctor', $data);
    }

    /**
     * Handle update doctor
     */
    public function update($id)
    {
        $doctor = $this->doctorModel->find($id);

        if (!$doctor) {
            session()->setFlashdata('error', 'Không tìm thấy bác sĩ!');
            return redirect()->to('/admin/doctors');
        }

        $fullname = trim($this->request->getPost('fullname'));
        $phone = trim($this->request->getPost('phone'));
        $identity_card = trim($this->request->getPost('identity_card')) ?: null;
        $address = trim($this->request->getPost('address'));
        $note = trim($this->request->getPost('note')) ?: null;

        if (empty($fullname) || empty($phone) || empty($address)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/doctors/edit/' . $id);
        }

        $data = [
            'doctor_name' => $fullname,
            'doctor_phone_number' => $phone,
            'doctor_identity_card' => $identity_card,
            'doctor_address' => $address,
            'doctor_note' => $note
        ];

        if ($this->doctorModel->update($id, $data)) {
            session()->setFlashdata('success', 'Cập nhật bác sĩ thành công!');
            return redirect()->to('/admin/doctors');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật bác sĩ. Vui lòng thử lại.');
            return redirect()->to('/admin/doctors/edit/' . $id);
        }
    }

    /**
     * Handle delete doctor
     */
    public function delete($id)
    {
        $doctor = $this->doctorModel->find($id);

        if (!$doctor) {
            session()->setFlashdata('error', 'Không tìm thấy bác sĩ!');
            return redirect()->to('/admin/doctors');
        }

        if ($this->doctorModel->delete($id)) {
            session()->setFlashdata('success', 'Xóa bác sĩ thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa bác sĩ. Vui lòng thử lại.');
        }

        return redirect()->to('/admin/doctors');
    }
}
