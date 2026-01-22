<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\PetModel;
use App\Models\CustomerModel;

class PetController extends BaseController
{
    protected $petModel;
    protected $customerModel;

    public function __construct()
    {
        $this->petModel = new PetModel();
        $this->customerModel = new CustomerModel();
    }

    /**
     * List all pets
     */
    public function index()
    {
        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $pets = $this->petModel->getPetsPaginated($limit, $offset);
        $totalPets = $this->petModel->getPetCount();
        $totalPages = ceil($totalPets / $limit);

        // Get customer names for each pet
        foreach ($pets as &$pet) {
            $customer = $this->customerModel->find($pet['customer_id']);
            $pet['customer_name'] = $customer['customer_name'] ?? '';
        }

        $data = [
            'title' => 'Danh sách thú cưng - UIT Petcare',
            'pets' => $pets,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPets' => $totalPets
        ];

        return view('admin/pet/pets', $data);
    }

    /**
     * Show add pet form
     */
    public function add()
    {
        $customers = $this->customerModel->findAll();
        
        $data = [
            'title' => 'Thêm thú cưng - UIT Petcare',
            'customers' => $customers
        ];

        return view('admin/pet/add_pet', $data);
    }

    /**
     * Handle add pet
     */
    public function store()
    {
        $customer_id = $this->request->getPost('customer_id');
        $name = trim($this->request->getPost('name'));
        $species = trim($this->request->getPost('species')) ?: null;
        $gender = $this->request->getPost('gender') ?: null;
        $dob = $this->request->getPost('dob') ?: null;
        $weight = $this->request->getPost('weight') ?: null;
        $sterilization = $this->request->getPost('sterilization') ?: null;
        $characteristic = trim($this->request->getPost('characteristic')) ?: null;
        $allergy = trim($this->request->getPost('allergy')) ?: null;

        if (empty($customer_id) || empty($name)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/pets/add');
        }

        $data = [
            'customer_id' => $customer_id,
            'pet_name' => $name,
            'pet_species' => $species,
            'pet_gender' => $gender,
            'pet_dob' => $dob,
            'pet_weight' => $weight,
            'pet_sterilization' => $sterilization,
            'pet_characteristic' => $characteristic,
            'pet_drug_allergy' => $allergy
        ];

        if ($this->petModel->insert($data)) {
            session()->setFlashdata('success', 'Thêm thú cưng thành công!');
            return redirect()->to('/admin/pets');
        } else {
            session()->setFlashdata('error', 'Không thể thêm thú cưng. Vui lòng thử lại.');
            return redirect()->to('/admin/pets/add');
        }
    }

    /**
     * Show edit pet form
     */
    public function edit($id)
    {
        $pet = $this->petModel->find($id);
        $customers = $this->customerModel->findAll();

        if (!$pet) {
            session()->setFlashdata('error', 'Không tìm thấy thú cưng!');
            return redirect()->to('/admin/pets');
        }

        $data = [
            'title' => 'Chỉnh sửa thú cưng - UIT Petcare',
            'pet' => $pet,
            'customers' => $customers
        ];

        return view('admin/pet/edit_pet', $data);
    }

    /**
     * Handle update pet
     */
    public function update($id)
    {
        $pet = $this->petModel->find($id);

        if (!$pet) {
            session()->setFlashdata('error', 'Không tìm thấy thú cưng!');
            return redirect()->to('/admin/pets');
        }

        $customer_id = $this->request->getPost('customer_id');
        $name = trim($this->request->getPost('name'));
        $species = trim($this->request->getPost('species')) ?: null;
        $gender = $this->request->getPost('gender') ?: null;
        $dob = $this->request->getPost('dob') ?: null;
        $weight = $this->request->getPost('weight') ?: null;
        $sterilization = $this->request->getPost('sterilization') ?: null;
        $characteristic = trim($this->request->getPost('characteristic')) ?: null;
        $allergy = trim($this->request->getPost('allergy')) ?: null;

        if (empty($customer_id) || empty($name)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/pets/edit/' . $id);
        }

        $data = [
            'customer_id' => $customer_id,
            'pet_name' => $name,
            'pet_species' => $species,
            'pet_gender' => $gender,
            'pet_dob' => $dob,
            'pet_weight' => $weight,
            'pet_sterilization' => $sterilization,
            'pet_characteristic' => $characteristic,
            'pet_drug_allergy' => $allergy
        ];

        if ($this->petModel->update($id, $data)) {
            session()->setFlashdata('success', 'Cập nhật thú cưng thành công!');
            return redirect()->to('/admin/pets');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật thú cưng. Vui lòng thử lại.');
            return redirect()->to('/admin/pets/edit/' . $id);
        }
    }

    /**
     * Handle delete pet
     */
    public function delete($id)
    {
        $pet = $this->petModel->find($id);

        if (!$pet) {
            session()->setFlashdata('error', 'Không tìm thấy thú cưng!');
            return redirect()->to('/admin/pets');
        }

        if ($this->petModel->delete($id)) {
            session()->setFlashdata('success', 'Xóa thú cưng thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa thú cưng. Vui lòng thử lại.');
        }

        return redirect()->to('/admin/pets');
    }
}
