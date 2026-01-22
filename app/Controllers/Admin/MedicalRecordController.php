<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\MedicalRecordModel;
use App\Models\VaccinationRecordModel;
use App\Models\CustomerModel;
use App\Models\PetModel;
use App\Models\DoctorModel;

class MedicalRecordController extends BaseController
{
    protected $medicalRecordModel;
    protected $vaccinationRecordModel;
    protected $customerModel;
    protected $petModel;
    protected $doctorModel;

    public function __construct()
    {
        $this->medicalRecordModel = new MedicalRecordModel();
        $this->vaccinationRecordModel = new VaccinationRecordModel();
        $this->customerModel = new CustomerModel();
        $this->petModel = new PetModel();
        $this->doctorModel = new DoctorModel();
    }

    /**
     * List all medical records
     */
    public function index()
    {
        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $records = $this->medicalRecordModel->getMedicalRecordsPaginated($limit, $offset);
        $totalRecords = $this->medicalRecordModel->countAllResults();
        $totalPages = ceil($totalRecords / $limit);

        // Get related data
        foreach ($records as &$record) {
            $customer = $this->customerModel->find($record['customer_id']);
            $pet = $this->petModel->find($record['pet_id']);
            $doctor = $this->doctorModel->find($record['doctor_id']);
            $record['customer_name'] = $customer['customer_name'] ?? '';
            $record['pet_name'] = $pet['pet_name'] ?? '';
            $record['doctor_name'] = $doctor['doctor_name'] ?? '';
        }

        $data = [
            'title' => 'Lịch sử khám bệnh - UIT Petcare',
            'records' => $records,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords
        ];

        return view('admin/medical_record/medical_records', $data);
    }

    /**
     * Show add medical record form
     */
    public function add()
    {
        $customers = $this->customerModel->findAll();
        $pets = $this->petModel->findAll();
        $doctors = $this->doctorModel->findAll();

        $data = [
            'title' => 'Tạo phiếu khám - UIT Petcare',
            'customers' => $customers,
            'pets' => $pets,
            'doctors' => $doctors
        ];

        return view('admin/medical_record/add_medical_record', $data);
    }

    /**
     * Handle add medical record
     */
    public function store()
    {
        $customer_id = $this->request->getPost('customer_id');
        $pet_id = $this->request->getPost('pet_id');
        $doctor_id = $this->request->getPost('doctor_id');
        $type = $this->request->getPost('type');
        $visit_date = $this->request->getPost('visit_date');
        $summary = trim($this->request->getPost('summary')) ?: null;
        $details = trim($this->request->getPost('details')) ?: null;

        if (empty($customer_id) || empty($pet_id) || empty($doctor_id) || empty($type) || empty($visit_date)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/medical-records/add');
        }

        $data = [
            'customer_id' => $customer_id,
            'pet_id' => $pet_id,
            'doctor_id' => $doctor_id,
            'medical_record_type' => $type,
            'medical_record_visit_date' => $visit_date,
            'medical_record_summary' => $summary,
            'medical_record_details' => $details
        ];

        $medicalRecordId = $this->medicalRecordModel->insert($data);

        if ($medicalRecordId) {
            // If type is Vaccine, add vaccination record
            if ($type === 'Vaccine') {
                $vaccine_name = trim($this->request->getPost('vaccine_name'));
                $batch_number = trim($this->request->getPost('batch_number')) ?: null;
                $next_injection_date = $this->request->getPost('next_injection_date') ?: null;

                if (!empty($vaccine_name)) {
                    $vaccinationData = [
                        'medical_record_id' => $medicalRecordId,
                        'vaccine_name' => $vaccine_name,
                        'batch_number' => $batch_number,
                        'next_injection_date' => $next_injection_date
                    ];
                    $this->vaccinationRecordModel->insert($vaccinationData);
                }
            }

            session()->setFlashdata('success', 'Tạo phiếu khám thành công!');
            return redirect()->to('/admin/medical-records');
        } else {
            session()->setFlashdata('error', 'Không thể tạo phiếu khám. Vui lòng thử lại.');
            return redirect()->to('/admin/medical-records/add');
        }
    }

    /**
     * Show edit medical record form
     */
    public function edit($id)
    {
        $record = $this->medicalRecordModel->find($id);

        if (!$record) {
            session()->setFlashdata('error', 'Không tìm thấy phiếu khám!');
            return redirect()->to('/admin/medical-records');
        }

        $customers = $this->customerModel->findAll();
        $pets = $this->petModel->findAll();
        $doctors = $this->doctorModel->findAll();
        $vaccination = $this->vaccinationRecordModel->getVaccinationByMedicalId($id);

        $data = [
            'title' => 'Chỉnh sửa phiếu khám - UIT Petcare',
            'record' => $record,
            'customers' => $customers,
            'pets' => $pets,
            'doctors' => $doctors,
            'vaccination' => $vaccination
        ];

        return view('admin/medical_record/edit_medical_record', $data);
    }

    /**
     * Handle update medical record
     */
    public function update($id)
    {
        $record = $this->medicalRecordModel->find($id);

        if (!$record) {
            session()->setFlashdata('error', 'Không tìm thấy phiếu khám!');
            return redirect()->to('/admin/medical-records');
        }

        $customer_id = $this->request->getPost('customer_id');
        $pet_id = $this->request->getPost('pet_id');
        $doctor_id = $this->request->getPost('doctor_id');
        $type = $this->request->getPost('type');
        $visit_date = $this->request->getPost('visit_date');
        $summary = trim($this->request->getPost('summary')) ?: null;
        $details = trim($this->request->getPost('details')) ?: null;

        if (empty($customer_id) || empty($pet_id) || empty($doctor_id) || empty($type) || empty($visit_date)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/medical-records/edit/' . $id);
        }

        $data = [
            'customer_id' => $customer_id,
            'pet_id' => $pet_id,
            'doctor_id' => $doctor_id,
            'medical_record_type' => $type,
            'medical_record_visit_date' => $visit_date,
            'medical_record_summary' => $summary,
            'medical_record_details' => $details
        ];

        if ($this->medicalRecordModel->update($id, $data)) {
            // Handle vaccination record
            $oldType = $record['medical_record_type'];
            
            if ($type === 'Vaccine') {
                $vaccine_name = trim($this->request->getPost('vaccine_name'));
                $batch_number = trim($this->request->getPost('batch_number')) ?: null;
                $next_injection_date = $this->request->getPost('next_injection_date') ?: null;

                $existingVaccination = $this->vaccinationRecordModel->getVaccinationByMedicalId($id);
                
                if ($existingVaccination) {
                    // Update existing
                    $this->vaccinationRecordModel->where('medical_record_id', $id)->set([
                        'vaccine_name' => $vaccine_name,
                        'batch_number' => $batch_number,
                        'next_injection_date' => $next_injection_date
                    ])->update();
                } else if (!empty($vaccine_name)) {
                    // Insert new
                    $this->vaccinationRecordModel->insert([
                        'medical_record_id' => $id,
                        'vaccine_name' => $vaccine_name,
                        'batch_number' => $batch_number,
                        'next_injection_date' => $next_injection_date
                    ]);
                }
            } else if ($oldType === 'Vaccine' && $type !== 'Vaccine') {
                // Delete vaccination record if type changed from Vaccine
                $this->vaccinationRecordModel->where('medical_record_id', $id)->delete();
            }

            session()->setFlashdata('success', 'Cập nhật phiếu khám thành công!');
            return redirect()->to('/admin/medical-records');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật phiếu khám. Vui lòng thử lại.');
            return redirect()->to('/admin/medical-records/edit/' . $id);
        }
    }

    /**
     * Handle delete medical record
     */
    public function delete($id)
    {
        $record = $this->medicalRecordModel->find($id);

        if (!$record) {
            session()->setFlashdata('error', 'Không tìm thấy phiếu khám!');
            return redirect()->to('/admin/medical-records');
        }

        // Delete vaccination record first (cascade)
        $this->vaccinationRecordModel->where('medical_record_id', $id)->delete();

        if ($this->medicalRecordModel->delete($id)) {
            session()->setFlashdata('success', 'Xóa phiếu khám thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa phiếu khám. Vui lòng thử lại.');
        }

        return redirect()->to('/admin/medical-records');
    }
}
