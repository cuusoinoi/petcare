<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\CustomerModel;
use App\Models\PetModel;
use App\Models\DoctorModel;
use App\Models\ServiceTypeModel;
use App\Models\GeneralSettingModel;

class BookingController extends BaseController
{
    protected $appointmentModel;
    protected $customerModel;
    protected $petModel;
    protected $doctorModel;
    protected $serviceTypeModel;
    protected $settingModel;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
        $this->customerModel = new CustomerModel();
        $this->petModel = new PetModel();
        $this->doctorModel = new DoctorModel();
        $this->serviceTypeModel = new ServiceTypeModel();
        $this->settingModel = new GeneralSettingModel();
    }

    /**
     * Trang đặt lịch
     */
    public function index()
    {
        // Kiểm tra đăng nhập
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/customer/login')->with('error', 'Vui lòng đăng nhập để đặt lịch');
        }

        $customerId = session()->get('customer_id');
        $pets = $this->petModel->where('customer_id', $customerId)->findAll();
        $doctors = $this->doctorModel->findAll();
        $services = $this->serviceTypeModel->findAll();
        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Đặt lịch hẹn - UIT Petcare',
            'pets' => $pets,
            'doctors' => $doctors,
            'services' => $services,
            'settings' => $settings
        ];

        return view('customer/booking/index', $data);
    }

    /**
     * Xử lý đặt lịch
     */
    public function create()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('/customer/booking');
        }

        // Kiểm tra đăng nhập
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/customer/login')->with('error', 'Vui lòng đăng nhập');
        }

        $customerId = session()->get('customer_id');
        $petId = $this->request->getPost('pet_id');
        $doctorId = $this->request->getPost('doctor_id');
        $serviceId = $this->request->getPost('service_id');
        $appointmentDate = $this->request->getPost('appointment_date');
        $appointmentTime = $this->request->getPost('appointment_time');
        $appointmentType = $this->request->getPost('appointment_type');
        $notes = $this->request->getPost('notes');

        // Validation
        if (empty($petId) || empty($appointmentDate) || empty($appointmentTime) || empty($appointmentType)) {
            return redirect()->back()->with('error', 'Vui lòng điền đầy đủ thông tin');
        }

        // Kiểm tra pet thuộc về customer
        $pet = $this->petModel->where('pet_id', $petId)
            ->where('customer_id', $customerId)
            ->first();

        if (!$pet) {
            return redirect()->back()->with('error', 'Thú cưng không hợp lệ');
        }

        // Tạo appointment
        $appointmentData = [
            'customer_id' => $customerId,
            'pet_id' => $petId,
            'doctor_id' => $doctorId ?: null,
            'service_type_id' => $serviceId ?: null,
            'appointment_date' => $appointmentDate . ' ' . $appointmentTime . ':00',
            'appointment_type' => $appointmentType,
            'status' => 'pending',
            'notes' => $notes
        ];

        $this->appointmentModel->insert($appointmentData);

        return redirect()->to('/customer/dashboard')->with('success', 'Đặt lịch thành công! Chúng tôi sẽ liên hệ xác nhận.');
    }

    /**
     * Danh sách lịch hẹn của khách hàng
     */
    public function myAppointments()
    {
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/customer/login');
        }

        $customerId = session()->get('customer_id');
        $appointments = $this->appointmentModel->getAppointmentsByCustomerId($customerId);
        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'Lịch hẹn của tôi - UIT Petcare',
            'appointments' => $appointments,
            'settings' => $settings
        ];

        return view('customer/booking/my_appointments', $data);
    }
}
