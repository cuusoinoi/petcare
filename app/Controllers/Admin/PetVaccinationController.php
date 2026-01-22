<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\PetVaccinationModel;
use App\Models\VaccineModel;
use App\Models\CustomerModel;
use App\Models\PetModel;
use App\Models\DoctorModel;

class PetVaccinationController extends BaseController
{
    protected $petVaccinationModel;
    protected $vaccineModel;
    protected $customerModel;
    protected $petModel;
    protected $doctorModel;

    public function __construct()
    {
        $this->petVaccinationModel = new PetVaccinationModel();
        $this->vaccineModel = new VaccineModel();
        $this->customerModel = new CustomerModel();
        $this->petModel = new PetModel();
        $this->doctorModel = new DoctorModel();
    }

    public function index()
    {
        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $vaccinations = $this->petVaccinationModel->getPetVaccinationsPaginated($limit, $offset);
        $totalVaccinations = $this->petVaccinationModel->countAllResults();
        $totalPages = ceil($totalVaccinations / $limit);

        // Get related data
        foreach ($vaccinations as &$vaccination) {
            $vaccine = $this->vaccineModel->find($vaccination['vaccine_id']);
            $customer = $this->customerModel->find($vaccination['customer_id']);
            $pet = $this->petModel->find($vaccination['pet_id']);
            $doctor = $this->doctorModel->find($vaccination['doctor_id']);
            $vaccination['vaccine_name'] = $vaccine['vaccine_name'] ?? '';
            $vaccination['customer_name'] = $customer['customer_name'] ?? '';
            $vaccination['pet_name'] = $pet['pet_name'] ?? '';
            $vaccination['doctor_name'] = $doctor['doctor_name'] ?? '';
        }

        $data = [
            'title' => 'Lịch sử tiêm chủng - UIT Petcare',
            'vaccinations' => $vaccinations,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalVaccinations' => $totalVaccinations
        ];

        return view('admin/pet_vaccination/pet_vaccinations', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Thêm tiêm chủng - UIT Petcare',
            'vaccines' => $this->vaccineModel->findAll(),
            'customers' => $this->customerModel->findAll(),
            'pets' => $this->petModel->findAll(),
            'doctors' => $this->doctorModel->findAll()
        ];
        return view('admin/pet_vaccination/add_pet_vaccination', $data);
    }

    public function store()
    {
        $vaccine_id = $this->request->getPost('vaccine_id');
        $customer_id = $this->request->getPost('customer_id');
        $pet_id = $this->request->getPost('pet_id');
        $doctor_id = $this->request->getPost('doctor_id');
        $vaccination_date = $this->request->getPost('vaccination_date');
        $next_vaccination_date = $this->request->getPost('next_vaccination_date') ?: null;
        $notes = trim($this->request->getPost('notes'));

        if (empty($vaccine_id) || empty($customer_id) || empty($pet_id) || empty($doctor_id) || empty($vaccination_date)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/pet-vaccinations/add');
        }

        $data = [
            'vaccine_id' => $vaccine_id,
            'customer_id' => $customer_id,
            'pet_id' => $pet_id,
            'doctor_id' => $doctor_id,
            'vaccination_date' => $vaccination_date,
            'next_vaccination_date' => $next_vaccination_date,
            'notes' => $notes
        ];

        if ($this->petVaccinationModel->insert($data)) {
            session()->setFlashdata('success', 'Thêm tiêm chủng thành công!');
            return redirect()->to('/admin/pet-vaccinations');
        } else {
            session()->setFlashdata('error', 'Không thể thêm tiêm chủng. Vui lòng thử lại.');
            return redirect()->to('/admin/pet-vaccinations/add');
        }
    }

    public function edit($id)
    {
        $vaccination = $this->petVaccinationModel->find($id);

        if (!$vaccination) {
            session()->setFlashdata('error', 'Không tìm thấy tiêm chủng!');
            return redirect()->to('/admin/pet-vaccinations');
        }

        $data = [
            'title' => 'Chỉnh sửa tiêm chủng - UIT Petcare',
            'vaccination' => $vaccination,
            'vaccines' => $this->vaccineModel->findAll(),
            'customers' => $this->customerModel->findAll(),
            'pets' => $this->petModel->findAll(),
            'doctors' => $this->doctorModel->findAll()
        ];

        return view('admin/pet_vaccination/edit_pet_vaccination', $data);
    }

    public function update($id)
    {
        $vaccination = $this->petVaccinationModel->find($id);

        if (!$vaccination) {
            session()->setFlashdata('error', 'Không tìm thấy tiêm chủng!');
            return redirect()->to('/admin/pet-vaccinations');
        }

        $vaccine_id = $this->request->getPost('vaccine_id');
        $customer_id = $this->request->getPost('customer_id');
        $pet_id = $this->request->getPost('pet_id');
        $doctor_id = $this->request->getPost('doctor_id');
        $vaccination_date = $this->request->getPost('vaccination_date');
        $next_vaccination_date = $this->request->getPost('next_vaccination_date') ?: null;
        $notes = trim($this->request->getPost('notes'));

        if (empty($vaccine_id) || empty($customer_id) || empty($pet_id) || empty($doctor_id) || empty($vaccination_date)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/pet-vaccinations/edit/' . $id);
        }

        $data = [
            'vaccine_id' => $vaccine_id,
            'customer_id' => $customer_id,
            'pet_id' => $pet_id,
            'doctor_id' => $doctor_id,
            'vaccination_date' => $vaccination_date,
            'next_vaccination_date' => $next_vaccination_date,
            'notes' => $notes
        ];

        if ($this->petVaccinationModel->update($id, $data)) {
            session()->setFlashdata('success', 'Cập nhật tiêm chủng thành công!');
            return redirect()->to('/admin/pet-vaccinations');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật tiêm chủng. Vui lòng thử lại.');
            return redirect()->to('/admin/pet-vaccinations/edit/' . $id);
        }
    }

    public function delete($id)
    {
        $vaccination = $this->petVaccinationModel->find($id);

        if (!$vaccination) {
            session()->setFlashdata('error', 'Không tìm thấy tiêm chủng!');
            return redirect()->to('/admin/pet-vaccinations');
        }

        if ($this->petVaccinationModel->delete($id)) {
            session()->setFlashdata('success', 'Xóa tiêm chủng thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa tiêm chủng. Vui lòng thử lại.');
        }

        return redirect()->to('/admin/pet-vaccinations');
    }
}
