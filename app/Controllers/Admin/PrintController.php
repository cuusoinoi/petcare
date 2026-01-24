<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\GeneralSettingModel;
use App\Models\InvoiceModel;
use App\Models\InvoiceDetailModel;
use App\Models\CustomerModel;
use App\Models\PetModel;
use App\Models\MedicalRecordModel;
use App\Models\TreatmentCourseModel;
use App\Models\TreatmentSessionModel;
use App\Models\DiagnosisModel;
use App\Models\PrescriptionModel;
use App\Models\DoctorModel;
use App\Models\PetEnclosureModel;
use App\Models\ServiceTypeModel;

class PrintController extends BaseController
{
    protected $settingModel;
    protected $invoiceModel;
    protected $invoiceDetailModel;
    protected $customerModel;
    protected $petModel;
    protected $medicalRecordModel;
    protected $treatmentCourseModel;
    protected $treatmentSessionModel;
    protected $diagnosisModel;
    protected $prescriptionModel;
    protected $doctorModel;
    protected $petEnclosureModel;
    protected $serviceTypeModel;

    public function __construct()
    {
        $this->settingModel = new GeneralSettingModel();
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceDetailModel = new InvoiceDetailModel();
        $this->customerModel = new CustomerModel();
        $this->petModel = new PetModel();
        $this->medicalRecordModel = new MedicalRecordModel();
        $this->treatmentCourseModel = new TreatmentCourseModel();
        $this->treatmentSessionModel = new TreatmentSessionModel();
        $this->diagnosisModel = new DiagnosisModel();
        $this->prescriptionModel = new PrescriptionModel();
        $this->doctorModel = new DoctorModel();
        $this->petEnclosureModel = new PetEnclosureModel();
        $this->serviceTypeModel = new ServiceTypeModel();
    }

    /**
     * Print invoice
     */
    public function invoice($id)
    {
        $invoice = $this->invoiceModel->find($id);

        if (!$invoice) {
            return redirect()->to('/admin/invoices');
        }

        $customer = $this->customerModel->find($invoice['customer_id']);
        $pet = $this->petModel->find($invoice['pet_id']);
        $details = $this->invoiceDetailModel->getDetailsByInvoiceId($id);
        $settings = $this->settingModel->getSettings();

        // Get service names for details
        foreach ($details as &$detail) {
            $service = $this->serviceTypeModel->find($detail['service_type_id']);
            $detail['service_name'] = $service['service_name'] ?? 'Dịch vụ';
        }

        $data = [
            'title' => 'In hóa đơn - UIT Petcare',
            'invoice' => $invoice,
            'customer' => $customer,
            'pet' => $pet,
            'details' => $details,
            'settings' => $settings
        ];

        return view('admin/print/invoice', $data);
    }

    /**
     * Print medical record
     */
    public function medicalRecord($id)
    {
        $record = $this->medicalRecordModel->find($id);

        if (!$record) {
            return redirect()->to('/admin/medical-records');
        }

        $customer = $this->customerModel->find($record['customer_id']);
        $pet = $this->petModel->find($record['pet_id']);
        $doctor = $this->doctorModel->find($record['doctor_id']);
        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'In phiếu khám - UIT Petcare',
            'record' => $record,
            'customer' => $customer,
            'pet' => $pet,
            'doctor' => $doctor,
            'settings' => $settings
        ];

        return view('admin/print/medical_record', $data);
    }

    /**
     * Print treatment session with diagnosis and prescription
     */
    public function treatmentSession($courseId, $sessionId)
    {
        $course = $this->treatmentCourseModel->find($courseId);
        $session = $this->treatmentSessionModel->find($sessionId);

        if (!$course || !$session) {
            return redirect()->to('/admin/treatment-courses');
        }

        $customer = $this->customerModel->find($course['customer_id']);
        $pet = $this->petModel->find($course['pet_id']);
        $doctor = $this->doctorModel->find($session['doctor_id']);
        $diagnosis = $this->diagnosisModel->getDiagnosisBySessionId($sessionId);
        $prescriptions = $this->prescriptionModel->getPrescriptionsWithMedicine($sessionId);
        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'In phiếu điều trị - UIT Petcare',
            'course' => $course,
            'session' => $session,
            'customer' => $customer,
            'pet' => $pet,
            'doctor' => $doctor,
            'diagnosis' => $diagnosis,
            'prescriptions' => $prescriptions,
            'settings' => $settings
        ];

        return view('admin/print/treatment_session', $data);
    }

    /**
     * Print pet enclosure checkout receipt
     */
    public function petEnclosure($id)
    {
        $enclosure = $this->petEnclosureModel->find($id);

        if (!$enclosure) {
            return redirect()->to('/admin/pet-enclosures');
        }

        $customer = $this->customerModel->find($enclosure['customer_id']);
        $pet = $this->petModel->find($enclosure['pet_id']);
        $settings = $this->settingModel->getSettings();

        $data = [
            'title' => 'In phiếu lưu chuồng - UIT Petcare',
            'enclosure' => $enclosure,
            'customer' => $customer,
            'pet' => $pet,
            'settings' => $settings
        ];

        return view('admin/print/pet_enclosure', $data);
    }
}
