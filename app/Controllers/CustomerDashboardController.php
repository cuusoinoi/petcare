<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\PetModel;
use App\Models\MedicalRecordModel;
use App\Models\PrescriptionModel;
use App\Models\PetVaccinationModel;
use App\Models\InvoiceModel;
use App\Models\AppointmentModel;
use App\Models\TreatmentCourseModel;
use App\Models\TreatmentSessionModel;
use App\Models\GeneralSettingModel;

class CustomerDashboardController extends BaseController
{
    protected $customerModel;
    protected $petModel;
    protected $medicalRecordModel;
    protected $prescriptionModel;
    protected $petVaccinationModel;
    protected $invoiceModel;
    protected $appointmentModel;
    protected $treatmentCourseModel;
    protected $treatmentSessionModel;
    protected $settingModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->petModel = new PetModel();
        $this->medicalRecordModel = new MedicalRecordModel();
        $this->prescriptionModel = new PrescriptionModel();
        $this->petVaccinationModel = new PetVaccinationModel();
        $this->invoiceModel = new InvoiceModel();
        $this->appointmentModel = new AppointmentModel();
        $this->treatmentCourseModel = new TreatmentCourseModel();
        $this->treatmentSessionModel = new TreatmentSessionModel();
        $this->settingModel = new GeneralSettingModel();
    }

    /**
     * Dashboard khách hàng
     */
    public function index()
    {
        // Kiểm tra đăng nhập
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/customer/login')->with('error', 'Vui lòng đăng nhập');
        }

        $customerId = session()->get('customer_id');
        $customer = $this->customerModel->find($customerId);
        $pets = $this->petModel->where('customer_id', $customerId)->findAll();
        
        // Thống kê
        $totalPets = count($pets);
        $totalAppointments = $this->appointmentModel->where('customer_id', $customerId)->countAllResults();
        $totalInvoices = $this->invoiceModel->where('customer_id', $customerId)->countAllResults();
        
        // Lịch hẹn sắp tới
        $upcomingAppointments = $this->appointmentModel
            ->select('appointments.*, pets.pet_name')
            ->join('pets', 'pets.pet_id = appointments.pet_id', 'left')
            ->where('appointments.customer_id', $customerId)
            ->where('appointments.appointment_date >=', date('Y-m-d H:i:s'))
            ->groupStart()
                ->where('appointments.status', 'pending')
                ->orWhere('appointments.status', 'confirmed')
            ->groupEnd()
            ->orderBy('appointments.appointment_date', 'ASC')
            ->limit(5)
            ->findAll();

        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Dashboard - UIT Petcare',
            'customer' => $customer,
            'pets' => $pets,
            'totalPets' => $totalPets,
            'totalAppointments' => $totalAppointments,
            'totalInvoices' => $totalInvoices,
            'upcomingAppointments' => $upcomingAppointments,
            'settings' => $settings
        ];

        return view('customer/dashboard/index', $data);
    }

    /**
     * API endpoint để lấy dữ liệu dashboard (cho auto-refresh)
     */
    public function getDashboardData()
    {
        // Kiểm tra đăng nhập
        if (session()->get('role') !== 'customer') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }

        $customerId = session()->get('customer_id');
        
        // Thống kê
        $totalPets = $this->petModel->where('customer_id', $customerId)->countAllResults();
        $totalAppointments = $this->appointmentModel->where('customer_id', $customerId)->countAllResults();
        $totalInvoices = $this->invoiceModel->where('customer_id', $customerId)->countAllResults();
        
        // Lịch hẹn sắp tới
        $upcomingAppointments = $this->appointmentModel
            ->select('appointments.*, pets.pet_name')
            ->join('pets', 'pets.pet_id = appointments.pet_id', 'left')
            ->where('appointments.customer_id', $customerId)
            ->where('appointments.appointment_date >=', date('Y-m-d H:i:s'))
            ->groupStart()
                ->where('appointments.status', 'pending')
                ->orWhere('appointments.status', 'confirmed')
            ->groupEnd()
            ->orderBy('appointments.appointment_date', 'ASC')
            ->limit(5)
            ->findAll();

        // Format appointments for JSON
        $formattedAppointments = [];
        foreach ($upcomingAppointments as $apt) {
            $formattedAppointments[] = [
                'appointment_date' => date('d/m/Y H:i', strtotime($apt['appointment_date'])),
                'pet_name' => $apt['pet_name'] ?? 'N/A',
                'appointment_type' => $apt['appointment_type'],
                'status' => $apt['status'],
                'status_text' => $apt['status'] === 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận',
                'status_bg' => $apt['status'] === 'confirmed' ? '#d4edda' : '#fff3cd',
                'status_color' => $apt['status'] === 'confirmed' ? '#155724' : '#856404'
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'totalPets' => $totalPets,
                'totalAppointments' => $totalAppointments,
                'totalInvoices' => $totalInvoices,
                'upcomingAppointments' => $formattedAppointments,
                'timestamp' => time()
            ]
        ]);
    }

    /**
     * Quản lý thông tin cá nhân
     */
    public function profile()
    {
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/customer/login');
        }

        $customerId = session()->get('customer_id');
        $customer = $this->customerModel->find($customerId);

        if (strtolower($this->request->getMethod()) === 'post') {
            $data = [
                'customer_name' => $this->request->getPost('customer_name'),
                'customer_email' => $this->request->getPost('customer_email'),
                'customer_address' => $this->request->getPost('customer_address')
            ];

            $this->customerModel->update($customerId, $data);
            return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
        }

        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Thông tin cá nhân - UIT Petcare',
            'customer' => $customer,
            'settings' => $settings
        ];

        return view('customer/dashboard/profile', $data);
    }

    /**
     * Quản lý thú cưng
     */
    public function pets()
    {
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/customer/login');
        }

        $customerId = session()->get('customer_id');
        $pets = $this->petModel->where('customer_id', $customerId)->findAll();

        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Thú cưng của tôi - UIT Petcare',
            'pets' => $pets,
            'settings' => $settings
        ];

        return view('customer/dashboard/pets', $data);
    }

    /**
     * Thêm thú cưng
     */
    public function addPet()
    {
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/customer/login');
        }

        if (strtolower($this->request->getMethod()) === 'post') {
            $customerId = session()->get('customer_id');
            $data = [
                'customer_id' => $customerId,
                'pet_name' => $this->request->getPost('pet_name'),
                'pet_species' => $this->request->getPost('pet_species'),
                'pet_gender' => $this->request->getPost('pet_gender'),
                'pet_dob' => $this->request->getPost('pet_dob'),
                'pet_weight' => $this->request->getPost('pet_weight'),
                'pet_sterilization' => $this->request->getPost('pet_sterilization'),
                'pet_characteristic' => $this->request->getPost('pet_characteristic'),
                'pet_drug_allergy' => $this->request->getPost('pet_drug_allergy')
            ];

            $this->petModel->insert($data);
            return redirect()->to('/customer/dashboard/pets')->with('success', 'Thêm thú cưng thành công!');
        }

        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Thêm thú cưng - UIT Petcare',
            'settings' => $settings
        ];

        return view('customer/dashboard/add_pet', $data);
    }

    /**
     * Lịch sử khám bệnh
     */
    public function medicalRecords($petId = null)
    {
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/customer/login');
        }

        $customerId = session()->get('customer_id');
        $pets = $this->petModel->where('customer_id', $customerId)->findAll();

        // Lấy petId từ query string nếu không có trong route
        if (!$petId) {
            $petId = $this->request->getGet('pet_id');
        }

        $records = [];
        if ($petId) {
            // Kiểm tra pet thuộc về customer
            $pet = $this->petModel->where('pet_id', $petId)
                ->where('customer_id', $customerId)
                ->first();
            
            if ($pet) {
                $records = $this->medicalRecordModel->db->table('medical_records')
                    ->select('medical_records.*, pets.pet_name, doctors.doctor_name')
                    ->join('pets', 'pets.pet_id = medical_records.pet_id', 'left')
                    ->join('doctors', 'doctors.doctor_id = medical_records.doctor_id', 'left')
                    ->where('medical_records.pet_id', $petId)
                    ->orderBy('medical_records.medical_record_visit_date', 'DESC')
                    ->get()
                    ->getResultArray();
            }
        } else {
            // Nếu không chọn pet, lấy tất cả records của customer
            $allPets = $this->petModel->where('customer_id', $customerId)->findAll();
            $petIds = array_column($allPets, 'pet_id');
            if (!empty($petIds)) {
                $records = $this->medicalRecordModel->db->table('medical_records')
                    ->select('medical_records.*, pets.pet_name, doctors.doctor_name')
                    ->join('pets', 'pets.pet_id = medical_records.pet_id', 'left')
                    ->join('doctors', 'doctors.doctor_id = medical_records.doctor_id', 'left')
                    ->whereIn('medical_records.pet_id', $petIds)
                    ->orderBy('medical_records.medical_record_visit_date', 'DESC')
                    ->get()
                    ->getResultArray();
            }
        }

        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Lịch sử khám bệnh - UIT Petcare',
            'pets' => $pets,
            'records' => $records,
            'selectedPetId' => $petId,
            'settings' => $settings
        ];

        return view('customer/dashboard/medical_records', $data);
    }

    /**
     * Đơn thuốc
     */
    public function prescriptions($petId = null)
    {
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/customer/login');
        }

        $customerId = session()->get('customer_id');
        $pets = $this->petModel->where('customer_id', $customerId)->findAll();

        // Lấy petId từ query string nếu không có trong route
        if (!$petId) {
            $petId = $this->request->getGet('pet_id');
        }

        $prescriptions = [];
        if ($petId) {
            $pet = $this->petModel->where('pet_id', $petId)
                ->where('customer_id', $customerId)
                ->first();
            
            if ($pet) {
                // Lấy prescriptions từ treatment sessions
                $sessions = $this->treatmentSessionModel
                    ->select('treatment_sessions.*, treatment_courses.pet_id')
                    ->join('treatment_courses', 'treatment_courses.treatment_course_id = treatment_sessions.treatment_course_id')
                    ->where('treatment_courses.pet_id', $petId)
                    ->findAll();

                $sessionIds = array_column($sessions, 'treatment_session_id');
                if (!empty($sessionIds)) {
                    $prescriptions = $this->prescriptionModel
                        ->select('prescriptions.*, medicines.medicine_name, medicines.medicine_route')
                        ->join('medicines', 'medicines.medicine_id = prescriptions.medicine_id')
                        ->whereIn('prescriptions.treatment_session_id', $sessionIds)
                        ->findAll();
                }
            }
        } else {
            // Nếu không chọn pet, lấy tất cả prescriptions của customer
            $allPets = $this->petModel->where('customer_id', $customerId)->findAll();
            $petIds = array_column($allPets, 'pet_id');
            if (!empty($petIds)) {
                $sessions = $this->treatmentSessionModel
                    ->select('treatment_sessions.*, treatment_courses.pet_id')
                    ->join('treatment_courses', 'treatment_courses.treatment_course_id = treatment_sessions.treatment_course_id')
                    ->whereIn('treatment_courses.pet_id', $petIds)
                    ->findAll();

                $sessionIds = array_column($sessions, 'treatment_session_id');
                if (!empty($sessionIds)) {
                    $prescriptions = $this->prescriptionModel
                        ->select('prescriptions.*, medicines.medicine_name, medicines.medicine_route')
                        ->join('medicines', 'medicines.medicine_id = prescriptions.medicine_id')
                        ->whereIn('prescriptions.treatment_session_id', $sessionIds)
                        ->findAll();
                }
            }
        }

        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Đơn thuốc - UIT Petcare',
            'pets' => $pets,
            'prescriptions' => $prescriptions,
            'selectedPetId' => $petId,
            'settings' => $settings
        ];

        return view('customer/dashboard/prescriptions', $data);
    }

    /**
     * Lịch tiêm chủng
     */
    public function vaccinations($petId = null)
    {
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/customer/login');
        }

        $customerId = session()->get('customer_id');
        $pets = $this->petModel->where('customer_id', $customerId)->findAll();

        // Lấy petId từ query string nếu không có trong route
        if (!$petId) {
            $petId = $this->request->getGet('pet_id');
        }

        $vaccinations = [];
        if ($petId) {
            $pet = $this->petModel->where('pet_id', $petId)
                ->where('customer_id', $customerId)
                ->first();
            
            if ($pet) {
                $vaccinations = $this->petVaccinationModel
                    ->select('pet_vaccinations.*, vaccines.vaccine_name')
                    ->join('vaccines', 'vaccines.vaccine_id = pet_vaccinations.vaccine_id')
                    ->where('pet_vaccinations.pet_id', $petId)
                    ->orderBy('pet_vaccinations.vaccination_date', 'DESC')
                    ->findAll();
            }
        } else {
            // Nếu không chọn pet, lấy tất cả vaccinations của customer
            $allPets = $this->petModel->where('customer_id', $customerId)->findAll();
            $petIds = array_column($allPets, 'pet_id');
            if (!empty($petIds)) {
                $vaccinations = $this->petVaccinationModel
                    ->select('pet_vaccinations.*, vaccines.vaccine_name')
                    ->join('vaccines', 'vaccines.vaccine_id = pet_vaccinations.vaccine_id')
                    ->whereIn('pet_vaccinations.pet_id', $petIds)
                    ->orderBy('pet_vaccinations.vaccination_date', 'DESC')
                    ->findAll();
            }
        }

        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Lịch tiêm chủng - UIT Petcare',
            'pets' => $pets,
            'vaccinations' => $vaccinations,
            'selectedPetId' => $petId,
            'settings' => $settings
        ];

        return view('customer/dashboard/vaccinations', $data);
    }

    /**
     * Hóa đơn
     */
    public function invoices()
    {
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/customer/login');
        }

        $customerId = session()->get('customer_id');
        $invoices = $this->invoiceModel
            ->select('invoices.*, pets.pet_name')
            ->join('pets', 'pets.pet_id = invoices.pet_id')
            ->where('invoices.customer_id', $customerId)
            ->orderBy('invoices.invoice_date', 'DESC')
            ->findAll();

        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Hóa đơn - UIT Petcare',
            'invoices' => $invoices,
            'settings' => $settings
        ];

        return view('customer/dashboard/invoices', $data);
    }

    /**
     * Xem chi tiết hóa đơn
     */
    public function viewInvoice($id)
    {
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/customer/login');
        }

        $customerId = session()->get('customer_id');
        
        // Lấy hóa đơn và kiểm tra quyền
        $invoice = $this->invoiceModel
            ->select('invoices.*, pets.pet_name, pets.pet_species, customers.customer_name, customers.customer_phone_number, customers.customer_address')
            ->join('pets', 'pets.pet_id = invoices.pet_id')
            ->join('customers', 'customers.customer_id = invoices.customer_id')
            ->where('invoices.invoice_id', $id)
            ->where('invoices.customer_id', $customerId)
            ->first();

        if (!$invoice) {
            session()->setFlashdata('error', 'Không tìm thấy hóa đơn hoặc bạn không có quyền xem hóa đơn này.');
            return redirect()->to('/customer/dashboard/invoices');
        }

        // Lấy chi tiết hóa đơn
        $invoiceDetails = $this->invoiceModel->db->table('invoice_details')
            ->select('invoice_details.*, service_types.service_name')
            ->join('service_types', 'service_types.service_type_id = invoice_details.service_type_id', 'left')
            ->where('invoice_details.invoice_id', $id)
            ->get()
            ->getResultArray();

        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Chi tiết hóa đơn - UIT Petcare',
            'invoice' => $invoice,
            'invoiceDetails' => $invoiceDetails,
            'settings' => $settings
        ];

        return view('customer/dashboard/view_invoice', $data);
    }
}
