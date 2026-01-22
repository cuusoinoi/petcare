<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\TreatmentCourseModel;
use App\Models\TreatmentSessionModel;
use App\Models\DiagnosisModel;
use App\Models\PrescriptionModel;
use App\Models\CustomerModel;
use App\Models\PetModel;
use App\Models\DoctorModel;
use App\Models\MedicineModel;

class TreatmentCourseController extends BaseController
{
    protected $treatmentCourseModel;
    protected $treatmentSessionModel;
    protected $diagnosisModel;
    protected $prescriptionModel;
    protected $customerModel;
    protected $petModel;
    protected $doctorModel;
    protected $medicineModel;

    public function __construct()
    {
        $this->treatmentCourseModel = new TreatmentCourseModel();
        $this->treatmentSessionModel = new TreatmentSessionModel();
        $this->diagnosisModel = new DiagnosisModel();
        $this->prescriptionModel = new PrescriptionModel();
        $this->customerModel = new CustomerModel();
        $this->petModel = new PetModel();
        $this->doctorModel = new DoctorModel();
        $this->medicineModel = new MedicineModel();
    }

    public function index()
    {
        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $courses = $this->treatmentCourseModel->getTreatmentCoursesPaginated($limit, $offset);
        $totalCourses = $this->treatmentCourseModel->countAllResults();
        $totalPages = ceil($totalCourses / $limit);

        foreach ($courses as &$course) {
            $customer = $this->customerModel->find($course['customer_id']);
            $pet = $this->petModel->find($course['pet_id']);
            $course['customer_name'] = $customer['customer_name'] ?? '';
            $course['pet_name'] = $pet['pet_name'] ?? '';
        }

        $data = [
            'title' => 'Danh sách liệu trình - UIT Petcare',
            'courses' => $courses,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCourses' => $totalCourses
        ];

        return view('admin/treatment_course/treatment_courses', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Thêm liệu trình - UIT Petcare',
            'customers' => $this->customerModel->findAll(),
            'pets' => $this->petModel->findAll()
        ];
        return view('admin/treatment_course/add_treatment_course', $data);
    }

    public function store()
    {
        $customer_id = $this->request->getPost('customer_id');
        $pet_id = $this->request->getPost('pet_id');
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date') ?: null;
        $status = $this->request->getPost('status') ?? 1;

        if (empty($customer_id) || empty($pet_id) || empty($start_date)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/treatment-courses/add');
        }

        $data = [
            'customer_id' => $customer_id,
            'pet_id' => $pet_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status
        ];

        if ($this->treatmentCourseModel->insert($data)) {
            session()->setFlashdata('success', 'Thêm liệu trình thành công!');
            return redirect()->to('/admin/treatment-courses');
        } else {
            session()->setFlashdata('error', 'Không thể thêm liệu trình. Vui lòng thử lại.');
            return redirect()->to('/admin/treatment-courses/add');
        }
    }

    public function edit($id)
    {
        $course = $this->treatmentCourseModel->find($id);

        if (!$course) {
            session()->setFlashdata('error', 'Không tìm thấy liệu trình!');
            return redirect()->to('/admin/treatment-courses');
        }

        $data = [
            'title' => 'Chỉnh sửa liệu trình - UIT Petcare',
            'course' => $course,
            'customers' => $this->customerModel->findAll(),
            'pets' => $this->petModel->findAll()
        ];

        return view('admin/treatment_course/edit_treatment_course', $data);
    }

    public function update($id)
    {
        $course = $this->treatmentCourseModel->find($id);

        if (!$course) {
            session()->setFlashdata('error', 'Không tìm thấy liệu trình!');
            return redirect()->to('/admin/treatment-courses');
        }

        $customer_id = $this->request->getPost('customer_id');
        $pet_id = $this->request->getPost('pet_id');
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date') ?: null;
        $status = $this->request->getPost('status') ?? 1;

        $data = [
            'customer_id' => $customer_id,
            'pet_id' => $pet_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status
        ];

        if ($this->treatmentCourseModel->update($id, $data)) {
            session()->setFlashdata('success', 'Cập nhật liệu trình thành công!');
            return redirect()->to('/admin/treatment-courses');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật liệu trình. Vui lòng thử lại.');
            return redirect()->to('/admin/treatment-courses/edit/' . $id);
        }
    }

    public function complete($id)
    {
        $course = $this->treatmentCourseModel->find($id);

        if (!$course) {
            session()->setFlashdata('error', 'Không tìm thấy liệu trình!');
            return redirect()->to('/admin/treatment-courses');
        }

        if ($this->treatmentCourseModel->completeTreatmentCourse($id, date('Y-m-d'))) {
            session()->setFlashdata('success', 'Kết thúc liệu trình thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể kết thúc liệu trình.');
        }

        return redirect()->to('/admin/treatment-courses');
    }

    public function delete($id)
    {
        $course = $this->treatmentCourseModel->find($id);

        if (!$course) {
            session()->setFlashdata('error', 'Không tìm thấy liệu trình!');
            return redirect()->to('/admin/treatment-courses');
        }

        if ($this->treatmentCourseModel->delete($id)) {
            session()->setFlashdata('success', 'Xóa liệu trình thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa liệu trình. Vui lòng thử lại.');
        }

        return redirect()->to('/admin/treatment-courses');
    }

    // ========== TREATMENT SESSIONS ==========

    public function sessions($courseId)
    {
        $course = $this->treatmentCourseModel->find($courseId);

        if (!$course) {
            session()->setFlashdata('error', 'Không tìm thấy liệu trình!');
            return redirect()->to('/admin/treatment-courses');
        }

        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $sessions = $this->treatmentSessionModel->getSessionsByCourseIdPaginated($courseId, $limit, $offset);
        $totalSessions = $this->treatmentSessionModel->countSessionsByCourseId($courseId);
        $totalPages = ceil($totalSessions / $limit);

        foreach ($sessions as &$session) {
            $doctor = $this->doctorModel->find($session['doctor_id']);
            $session['doctor_name'] = $doctor['doctor_name'] ?? '';
        }

        $customer = $this->customerModel->find($course['customer_id']);
        $pet = $this->petModel->find($course['pet_id']);

        $data = [
            'title' => 'Buổi điều trị - UIT Petcare',
            'course' => $course,
            'customer' => $customer,
            'pet' => $pet,
            'sessions' => $sessions,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalSessions' => $totalSessions
        ];

        return view('admin/treatment_course/treatment_sessions', $data);
    }

    public function addSession($courseId)
    {
        $course = $this->treatmentCourseModel->find($courseId);

        if (!$course) {
            session()->setFlashdata('error', 'Không tìm thấy liệu trình!');
            return redirect()->to('/admin/treatment-courses');
        }

        $customer = $this->customerModel->find($course['customer_id']);
        $pet = $this->petModel->find($course['pet_id']);

        $data = [
            'title' => 'Thêm buổi điều trị - UIT Petcare',
            'course' => $course,
            'customer' => $customer,
            'pet' => $pet,
            'doctors' => $this->doctorModel->findAll()
        ];

        return view('admin/treatment_course/add_treatment_session', $data);
    }

    public function storeSession($courseId)
    {
        $course = $this->treatmentCourseModel->find($courseId);

        if (!$course) {
            session()->setFlashdata('error', 'Không tìm thấy liệu trình!');
            return redirect()->to('/admin/treatment-courses');
        }

        $doctor_id = $this->request->getPost('doctor_id');
        $datetime = $this->request->getPost('datetime');
        $temperature = $this->request->getPost('temperature') ?: null;
        $weight = $this->request->getPost('weight') ?: null;
        $pulse_rate = $this->request->getPost('pulse_rate') ?: null;
        $respiratory_rate = $this->request->getPost('respiratory_rate') ?: null;
        $overall_notes = trim($this->request->getPost('overall_notes'));

        if (empty($doctor_id) || empty($datetime)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/treatment-courses/' . $courseId . '/sessions/add');
        }

        $data = [
            'treatment_course_id' => $courseId,
            'doctor_id' => $doctor_id,
            'treatment_session_datetime' => $datetime,
            'temperature' => $temperature,
            'weight' => $weight,
            'pulse_rate' => $pulse_rate,
            'respiratory_rate' => $respiratory_rate,
            'overall_notes' => $overall_notes
        ];

        if ($this->treatmentSessionModel->insert($data)) {
            session()->setFlashdata('success', 'Thêm buổi điều trị thành công!');
            return redirect()->to('/admin/treatment-courses/' . $courseId . '/sessions');
        } else {
            session()->setFlashdata('error', 'Không thể thêm buổi điều trị. Vui lòng thử lại.');
            return redirect()->to('/admin/treatment-courses/' . $courseId . '/sessions/add');
        }
    }

    public function editSession($courseId, $sessionId)
    {
        $course = $this->treatmentCourseModel->find($courseId);
        $session = $this->treatmentSessionModel->find($sessionId);

        if (!$course || !$session) {
            session()->setFlashdata('error', 'Không tìm thấy dữ liệu!');
            return redirect()->to('/admin/treatment-courses');
        }

        $customer = $this->customerModel->find($course['customer_id']);
        $pet = $this->petModel->find($course['pet_id']);

        $data = [
            'title' => 'Chỉnh sửa buổi điều trị - UIT Petcare',
            'course' => $course,
            'session' => $session,
            'customer' => $customer,
            'pet' => $pet,
            'doctors' => $this->doctorModel->findAll()
        ];

        return view('admin/treatment_course/edit_treatment_session', $data);
    }

    public function updateSession($courseId, $sessionId)
    {
        $session = $this->treatmentSessionModel->find($sessionId);

        if (!$session) {
            session()->setFlashdata('error', 'Không tìm thấy buổi điều trị!');
            return redirect()->to('/admin/treatment-courses/' . $courseId . '/sessions');
        }

        $doctor_id = $this->request->getPost('doctor_id');
        $datetime = $this->request->getPost('datetime');
        $temperature = $this->request->getPost('temperature') ?: null;
        $weight = $this->request->getPost('weight') ?: null;
        $pulse_rate = $this->request->getPost('pulse_rate') ?: null;
        $respiratory_rate = $this->request->getPost('respiratory_rate') ?: null;
        $overall_notes = trim($this->request->getPost('overall_notes'));

        $data = [
            'doctor_id' => $doctor_id,
            'treatment_session_datetime' => $datetime,
            'temperature' => $temperature,
            'weight' => $weight,
            'pulse_rate' => $pulse_rate,
            'respiratory_rate' => $respiratory_rate,
            'overall_notes' => $overall_notes
        ];

        if ($this->treatmentSessionModel->update($sessionId, $data)) {
            session()->setFlashdata('success', 'Cập nhật buổi điều trị thành công!');
            return redirect()->to('/admin/treatment-courses/' . $courseId . '/sessions');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật buổi điều trị. Vui lòng thử lại.');
            return redirect()->to('/admin/treatment-courses/' . $courseId . '/sessions/edit/' . $sessionId);
        }
    }

    public function deleteSession($courseId, $sessionId)
    {
        $session = $this->treatmentSessionModel->find($sessionId);

        if (!$session) {
            session()->setFlashdata('error', 'Không tìm thấy buổi điều trị!');
            return redirect()->to('/admin/treatment-courses/' . $courseId . '/sessions');
        }

        // Delete related diagnoses and prescriptions
        $this->diagnosisModel->where('treatment_session_id', $sessionId)->delete();
        $this->prescriptionModel->where('treatment_session_id', $sessionId)->delete();

        if ($this->treatmentSessionModel->delete($sessionId)) {
            session()->setFlashdata('success', 'Xóa buổi điều trị thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa buổi điều trị.');
        }

        return redirect()->to('/admin/treatment-courses/' . $courseId . '/sessions');
    }

    // ========== DIAGNOSIS ==========

    public function diagnosis($courseId, $sessionId)
    {
        $course = $this->treatmentCourseModel->find($courseId);
        $session = $this->treatmentSessionModel->find($sessionId);

        if (!$course || !$session) {
            session()->setFlashdata('error', 'Không tìm thấy dữ liệu!');
            return redirect()->to('/admin/treatment-courses');
        }

        $diagnosis = $this->diagnosisModel->getDiagnosisBySessionId($sessionId);
        $customer = $this->customerModel->find($course['customer_id']);
        $pet = $this->petModel->find($course['pet_id']);
        $doctor = $this->doctorModel->find($session['doctor_id']);

        $data = [
            'title' => 'Chẩn đoán - UIT Petcare',
            'course' => $course,
            'session' => $session,
            'diagnosis' => $diagnosis,
            'customer' => $customer,
            'pet' => $pet,
            'doctor' => $doctor
        ];

        return view('admin/treatment_course/diagnosis', $data);
    }

    public function saveDiagnosis($courseId, $sessionId)
    {
        $diagnosis_name = trim($this->request->getPost('diagnosis_name'));
        $diagnosis_type = trim($this->request->getPost('diagnosis_type'));
        $clinical_tests = trim($this->request->getPost('clinical_tests'));
        $notes = trim($this->request->getPost('notes'));

        $existingDiagnosis = $this->diagnosisModel->getDiagnosisBySessionId($sessionId);

        $data = [
            'treatment_session_id' => $sessionId,
            'diagnosis_name' => $diagnosis_name,
            'diagnosis_type' => $diagnosis_type,
            'clinical_tests' => $clinical_tests,
            'notes' => $notes
        ];

        if ($existingDiagnosis) {
            $this->diagnosisModel->update($existingDiagnosis['diagnosis_id'], $data);
        } else {
            $this->diagnosisModel->insert($data);
        }

        session()->setFlashdata('success', 'Lưu chẩn đoán thành công!');
        return redirect()->to('/admin/treatment-courses/' . $courseId . '/sessions/' . $sessionId . '/diagnosis');
    }

    // ========== PRESCRIPTION ==========

    public function prescription($courseId, $sessionId)
    {
        $course = $this->treatmentCourseModel->find($courseId);
        $session = $this->treatmentSessionModel->find($sessionId);

        if (!$course || !$session) {
            session()->setFlashdata('error', 'Không tìm thấy dữ liệu!');
            return redirect()->to('/admin/treatment-courses');
        }

        $prescriptions = $this->prescriptionModel->getPrescriptionsWithMedicine($sessionId);
        $customer = $this->customerModel->find($course['customer_id']);
        $pet = $this->petModel->find($course['pet_id']);
        $doctor = $this->doctorModel->find($session['doctor_id']);

        $data = [
            'title' => 'Đơn thuốc - UIT Petcare',
            'course' => $course,
            'session' => $session,
            'prescriptions' => $prescriptions,
            'customer' => $customer,
            'pet' => $pet,
            'doctor' => $doctor,
            'medicines' => $this->medicineModel->findAll()
        ];

        return view('admin/treatment_course/prescription', $data);
    }

    public function addPrescription($courseId, $sessionId)
    {
        $medicine_id = $this->request->getPost('medicine_id');
        $treatment_type = trim($this->request->getPost('treatment_type'));
        $dosage = trim($this->request->getPost('dosage'));
        $unit = trim($this->request->getPost('unit'));
        $frequency = trim($this->request->getPost('frequency'));
        $status = $this->request->getPost('status') ?? 1;
        $notes = trim($this->request->getPost('notes'));

        if (empty($medicine_id)) {
            session()->setFlashdata('error', 'Vui lòng chọn thuốc.');
            return redirect()->to('/admin/treatment-courses/' . $courseId . '/sessions/' . $sessionId . '/prescription');
        }

        $data = [
            'treatment_session_id' => $sessionId,
            'medicine_id' => $medicine_id,
            'treatment_type' => $treatment_type,
            'dosage' => $dosage,
            'unit' => $unit,
            'frequency' => $frequency,
            'status' => $status,
            'notes' => $notes
        ];

        if ($this->prescriptionModel->insert($data)) {
            session()->setFlashdata('success', 'Thêm thuốc vào đơn thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể thêm thuốc.');
        }

        return redirect()->to('/admin/treatment-courses/' . $courseId . '/sessions/' . $sessionId . '/prescription');
    }

    public function deletePrescription($courseId, $sessionId, $prescriptionId)
    {
        if ($this->prescriptionModel->delete($prescriptionId)) {
            session()->setFlashdata('success', 'Xóa thuốc khỏi đơn thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa thuốc.');
        }

        return redirect()->to('/admin/treatment-courses/' . $courseId . '/sessions/' . $sessionId . '/prescription');
    }
}
