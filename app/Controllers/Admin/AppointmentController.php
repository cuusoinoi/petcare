<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\AppointmentModel;
use App\Models\CustomerModel;
use App\Models\PetModel;
use App\Models\DoctorModel;
use App\Models\ServiceTypeModel;

class AppointmentController extends BaseController
{
    protected $appointmentModel;
    protected $customerModel;
    protected $petModel;
    protected $doctorModel;
    protected $serviceTypeModel;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
        $this->customerModel = new CustomerModel();
        $this->petModel = new PetModel();
        $this->doctorModel = new DoctorModel();
        $this->serviceTypeModel = new ServiceTypeModel();
    }

    /**
     * Danh sách lịch hẹn
     */
    public function index()
    {
        $limit = 20;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        // Lọc theo trạng thái
        $status = $this->request->getGet('status');
        
        // Build query for counting
        $countBuilder = $this->appointmentModel->db->table('appointments');
        if ($status && in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            $countBuilder->where('appointments.status', $status);
        }
        $totalAppointments = $countBuilder->countAllResults();
        
        // Build query for data
        $builder = $this->appointmentModel->select('appointments.*, customers.customer_name, pets.pet_name, doctors.doctor_name, service_types.service_name')
            ->join('customers', 'customers.customer_id = appointments.customer_id', 'left')
            ->join('pets', 'pets.pet_id = appointments.pet_id', 'left')
            ->join('doctors', 'doctors.doctor_id = appointments.doctor_id', 'left')
            ->join('service_types', 'service_types.service_type_id = appointments.service_type_id', 'left')
            ->orderBy('appointments.appointment_date', 'DESC');

        if ($status && in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            $builder->where('appointments.status', $status);
        }

        $appointments = $builder->findAll($limit, $offset);
        $totalPages = ceil($totalAppointments / $limit);

        $data = [
            'title' => 'Quản lý lịch hẹn - UIT Petcare',
            'appointments' => $appointments,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalAppointments' => $totalAppointments,
            'currentStatus' => $status
        ];

        return view('admin/appointment/appointments', $data);
    }

    /**
     * Cập nhật trạng thái lịch hẹn
     */
    public function updateStatus($id)
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('/admin/appointments');
        }

        $status = $this->request->getPost('status');
        
        if (!in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            session()->setFlashdata('error', 'Trạng thái không hợp lệ');
            return redirect()->back();
        }

        $appointment = $this->appointmentModel->find($id);
        if (!$appointment) {
            session()->setFlashdata('error', 'Không tìm thấy lịch hẹn');
            return redirect()->back();
        }

        $this->appointmentModel->update($id, ['status' => $status]);
        
        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];

        session()->setFlashdata('success', 'Cập nhật trạng thái thành công: ' . ($statusLabels[$status] ?? $status));
        return redirect()->back();
    }

    /**
     * Xem chi tiết lịch hẹn
     */
    public function view($id)
    {
        $builder = $this->appointmentModel->db->table('appointments');
        $appointment = $builder->select('appointments.*, customers.customer_name, customers.customer_phone_number, pets.pet_name, pets.pet_species, doctors.doctor_name, service_types.service_name')
            ->join('customers', 'customers.customer_id = appointments.customer_id', 'left')
            ->join('pets', 'pets.pet_id = appointments.pet_id', 'left')
            ->join('doctors', 'doctors.doctor_id = appointments.doctor_id', 'left')
            ->join('service_types', 'service_types.service_type_id = appointments.service_type_id', 'left')
            ->where('appointments.appointment_id', $id)
            ->get()
            ->getRowArray();

        if (!$appointment) {
            session()->setFlashdata('error', 'Không tìm thấy lịch hẹn');
            return redirect()->to('/admin/appointments');
        }

        $doctors = $this->doctorModel->findAll();
        $services = $this->serviceTypeModel->findAll();

        $data = [
            'title' => 'Chi tiết lịch hẹn - UIT Petcare',
            'appointment' => $appointment,
            'doctors' => $doctors,
            'services' => $services
        ];

        return view('admin/appointment/view', $data);
    }

    /**
     * Cập nhật thông tin lịch hẹn
     */
    public function update($id)
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('/admin/appointments');
        }

        $appointment = $this->appointmentModel->find($id);
        if (!$appointment) {
            session()->setFlashdata('error', 'Không tìm thấy lịch hẹn');
            return redirect()->back();
        }

        $data = [
            'doctor_id' => $this->request->getPost('doctor_id') ?: null,
            'service_type_id' => $this->request->getPost('service_type_id') ?: null,
            'appointment_date' => $this->request->getPost('appointment_date'),
            'appointment_type' => $this->request->getPost('appointment_type'),
            'status' => $this->request->getPost('status'),
            'notes' => $this->request->getPost('notes') ?: null
        ];

        $this->appointmentModel->update($id, $data);
        session()->setFlashdata('success', 'Cập nhật lịch hẹn thành công!');
        return redirect()->to('/admin/appointments');
    }

    /**
     * Xóa lịch hẹn
     */
    public function delete($id)
    {
        $appointment = $this->appointmentModel->find($id);
        if (!$appointment) {
            session()->setFlashdata('error', 'Không tìm thấy lịch hẹn');
            return redirect()->back();
        }

        $this->appointmentModel->delete($id);
        session()->setFlashdata('success', 'Xóa lịch hẹn thành công!');
        return redirect()->to('/admin/appointments');
    }
}
