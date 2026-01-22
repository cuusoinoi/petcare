<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\PetEnclosureModel;
use App\Models\CustomerModel;
use App\Models\PetModel;
use App\Models\InvoiceModel;
use App\Models\InvoiceDetailModel;
use App\Models\ServiceTypeModel;
use App\Models\GeneralSettingModel;

class PetEnclosureController extends BaseController
{
    protected $petEnclosureModel;
    protected $customerModel;
    protected $petModel;
    protected $invoiceModel;
    protected $invoiceDetailModel;
    protected $serviceTypeModel;
    protected $generalSettingModel;

    public function __construct()
    {
        $this->petEnclosureModel = new PetEnclosureModel();
        $this->customerModel = new CustomerModel();
        $this->petModel = new PetModel();
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceDetailModel = new InvoiceDetailModel();
        $this->serviceTypeModel = new ServiceTypeModel();
        $this->generalSettingModel = new GeneralSettingModel();
    }

    /**
     * List all pet enclosures
     */
    public function index()
    {
        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $enclosures = $this->petEnclosureModel->getPetEnclosuresPaginated($limit, $offset);
        $totalEnclosures = $this->petEnclosureModel->countAllResults();
        $totalPages = ceil($totalEnclosures / $limit);

        // Get related data
        foreach ($enclosures as &$enclosure) {
            $customer = $this->customerModel->find($enclosure['customer_id']);
            $pet = $this->petModel->find($enclosure['pet_id']);
            $enclosure['customer_name'] = $customer['customer_name'] ?? '';
            $enclosure['pet_name'] = $pet['pet_name'] ?? '';
        }

        $data = [
            'title' => 'Danh sách lưu chuồng - UIT Petcare',
            'enclosures' => $enclosures,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalEnclosures' => $totalEnclosures
        ];

        return view('admin/pet_enclosure/pet_enclosures', $data);
    }

    /**
     * Show add pet enclosure form
     */
    public function add()
    {
        $customers = $this->customerModel->findAll();
        $pets = $this->petModel->findAll();
        $settings = $this->generalSettingModel->getSettings();

        $data = [
            'title' => 'Thêm lưu chuồng - UIT Petcare',
            'customers' => $customers,
            'pets' => $pets,
            'settings' => $settings
        ];

        return view('admin/pet_enclosure/add_pet_enclosure', $data);
    }

    /**
     * Handle add pet enclosure
     */
    public function store()
    {
        $customer_id = $this->request->getPost('customer_id');
        $pet_id = $this->request->getPost('pet_id');
        $enclosure_number = trim($this->request->getPost('enclosure_number'));
        $check_in_date = $this->request->getPost('check_in_date');
        $check_out_date = $this->request->getPost('check_out_date') ?: null;
        $daily_rate = (int)$this->request->getPost('daily_rate');
        $deposit = (int)$this->request->getPost('deposit') ?: 0;
        $emergency_limit = (int)$this->request->getPost('emergency_limit') ?: 0;
        $note = trim($this->request->getPost('note')) ?: null;
        $status = $this->request->getPost('status') ?? 'Check In';

        if (empty($customer_id) || empty($pet_id) || empty($enclosure_number) || empty($check_in_date)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/pet-enclosures/add');
        }

        $data = [
            'customer_id' => $customer_id,
            'pet_id' => $pet_id,
            'pet_enclosure_number' => $enclosure_number,
            'check_in_date' => $check_in_date,
            'check_out_date' => $check_out_date,
            'daily_rate' => $daily_rate,
            'deposit' => $deposit,
            'emergency_limit' => $emergency_limit,
            'pet_enclosure_note' => $note,
            'pet_enclosure_status' => $status
        ];

        if ($this->petEnclosureModel->insert($data)) {
            session()->setFlashdata('success', 'Thêm lưu chuồng thành công!');
            return redirect()->to('/admin/pet-enclosures');
        } else {
            session()->setFlashdata('error', 'Không thể thêm lưu chuồng. Vui lòng thử lại.');
            return redirect()->to('/admin/pet-enclosures/add');
        }
    }

    /**
     * Show edit pet enclosure form
     */
    public function edit($id)
    {
        $enclosure = $this->petEnclosureModel->find($id);

        if (!$enclosure) {
            session()->setFlashdata('error', 'Không tìm thấy lưu chuồng!');
            return redirect()->to('/admin/pet-enclosures');
        }

        $customers = $this->customerModel->findAll();
        $pets = $this->petModel->findAll();
        $settings = $this->generalSettingModel->getSettings();

        $data = [
            'title' => 'Chỉnh sửa lưu chuồng - UIT Petcare',
            'enclosure' => $enclosure,
            'customers' => $customers,
            'pets' => $pets,
            'settings' => $settings
        ];

        return view('admin/pet_enclosure/edit_pet_enclosure', $data);
    }

    /**
     * Handle update pet enclosure
     */
    public function update($id)
    {
        $enclosure = $this->petEnclosureModel->find($id);

        if (!$enclosure) {
            session()->setFlashdata('error', 'Không tìm thấy lưu chuồng!');
            return redirect()->to('/admin/pet-enclosures');
        }

        $customer_id = $this->request->getPost('customer_id');
        $pet_id = $this->request->getPost('pet_id');
        $enclosure_number = trim($this->request->getPost('enclosure_number'));
        $check_in_date = $this->request->getPost('check_in_date');
        $check_out_date = $this->request->getPost('check_out_date') ?: null;
        $daily_rate = (int)$this->request->getPost('daily_rate');
        $deposit = (int)$this->request->getPost('deposit') ?: 0;
        $emergency_limit = (int)$this->request->getPost('emergency_limit') ?: 0;
        $note = trim($this->request->getPost('note')) ?: null;
        $status = $this->request->getPost('status') ?? 'Check In';

        if (empty($customer_id) || empty($pet_id) || empty($enclosure_number) || empty($check_in_date)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/pet-enclosures/edit/' . $id);
        }

        $data = [
            'customer_id' => $customer_id,
            'pet_id' => $pet_id,
            'pet_enclosure_number' => $enclosure_number,
            'check_in_date' => $check_in_date,
            'check_out_date' => $check_out_date,
            'daily_rate' => $daily_rate,
            'deposit' => $deposit,
            'emergency_limit' => $emergency_limit,
            'pet_enclosure_note' => $note,
            'pet_enclosure_status' => $status
        ];

        if ($this->petEnclosureModel->update($id, $data)) {
            session()->setFlashdata('success', 'Cập nhật lưu chuồng thành công!');
            return redirect()->to('/admin/pet-enclosures');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật lưu chuồng. Vui lòng thử lại.');
            return redirect()->to('/admin/pet-enclosures/edit/' . $id);
        }
    }

    /**
     * Show checkout page
     */
    public function checkout($id)
    {
        $enclosure = $this->petEnclosureModel->find($id);

        if (!$enclosure) {
            session()->setFlashdata('error', 'Không tìm thấy lưu chuồng!');
            return redirect()->to('/admin/pet-enclosures');
        }

        $customer = $this->customerModel->find($enclosure['customer_id']);
        $pet = $this->petModel->find($enclosure['pet_id']);
        $settings = $this->generalSettingModel->getSettings();
        $serviceTypes = $this->serviceTypeModel->findAll();

        // Calculate days and fees
        $checkInDate = new \DateTime($enclosure['check_in_date']);
        $checkOutDate = new \DateTime(date('Y-m-d H:i:s'));
        $days = $checkInDate->diff($checkOutDate)->days + 1;
        $dailyRate = $enclosure['daily_rate'];
        $enclosureFee = $days * $dailyRate;

        // Calculate overtime fee
        $overtimeFee = 0;
        $checkoutHour = $settings['checkout_hour'] ?? '18:00:00';
        $currentTime = date('H:i:s');
        if ($currentTime > $checkoutHour) {
            $checkoutTime = new \DateTime($checkoutHour);
            $currentTimeObj = new \DateTime($currentTime);
            $overtimeHours = ceil(($currentTimeObj->getTimestamp() - $checkoutTime->getTimestamp()) / 3600);
            $overtimeFee = $overtimeHours * ($settings['overtime_fee_per_hour'] ?? 0);
        }

        $data = [
            'title' => 'Checkout lưu chuồng - UIT Petcare',
            'enclosure' => $enclosure,
            'customer' => $customer,
            'pet' => $pet,
            'settings' => $settings,
            'serviceTypes' => $serviceTypes,
            'days' => $days,
            'enclosureFee' => $enclosureFee,
            'overtimeFee' => $overtimeFee
        ];

        return view('admin/pet_enclosure/checkout_invoice', $data);
    }

    /**
     * Handle checkout and create invoice
     */
    public function processCheckout($id)
    {
        $enclosure = $this->petEnclosureModel->find($id);

        if (!$enclosure) {
            session()->setFlashdata('error', 'Không tìm thấy lưu chuồng!');
            return redirect()->to('/admin/pet-enclosures');
        }

        $checkOutDate = date('Y-m-d H:i:s');
        $discount = (int)$this->request->getPost('discount') ?: 0;
        $subtotal = (int)$this->request->getPost('subtotal');
        $totalAmount = (int)$this->request->getPost('total_amount');

        // Update pet enclosure status
        $this->petEnclosureModel->update($id, [
            'check_out_date' => $checkOutDate,
            'pet_enclosure_status' => 'Check Out'
        ]);

        // Create invoice
        $invoiceData = [
            'customer_id' => $enclosure['customer_id'],
            'pet_id' => $enclosure['pet_id'],
            'pet_enclosure_id' => $id,
            'invoice_date' => date('Y-m-d'),
            'discount' => $discount,
            'subtotal' => $subtotal,
            'deposit' => $enclosure['deposit'],
            'total_amount' => $totalAmount
        ];

        $invoiceId = $this->invoiceModel->insert($invoiceData);

        if ($invoiceId) {
            // Add invoice details
            $serviceIds = $this->request->getPost('service_ids') ?? [];
            $quantities = $this->request->getPost('quantities') ?? [];
            $unitPrices = $this->request->getPost('unit_prices') ?? [];
            $totalPrices = $this->request->getPost('total_prices') ?? [];

            for ($i = 0; $i < count($serviceIds); $i++) {
                if (!empty($serviceIds[$i])) {
                    $this->invoiceDetailModel->insert([
                        'invoice_id' => $invoiceId,
                        'service_type_id' => $serviceIds[$i],
                        'quantity' => $quantities[$i] ?? 1,
                        'unit_price' => $unitPrices[$i] ?? 0,
                        'total_price' => $totalPrices[$i] ?? 0
                    ]);
                }
            }

            session()->setFlashdata('success', 'Checkout thành công! Hóa đơn đã được tạo.');
            return redirect()->to('/admin/invoices');
        } else {
            session()->setFlashdata('error', 'Không thể tạo hóa đơn. Vui lòng thử lại.');
            return redirect()->to('/admin/pet-enclosures/checkout/' . $id);
        }
    }

    /**
     * Handle delete pet enclosure
     */
    public function delete($id)
    {
        $enclosure = $this->petEnclosureModel->find($id);

        if (!$enclosure) {
            session()->setFlashdata('error', 'Không tìm thấy lưu chuồng!');
            return redirect()->to('/admin/pet-enclosures');
        }

        if ($this->petEnclosureModel->delete($id)) {
            session()->setFlashdata('success', 'Xóa lưu chuồng thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa lưu chuồng. Vui lòng thử lại.');
        }

        return redirect()->to('/admin/pet-enclosures');
    }
}
