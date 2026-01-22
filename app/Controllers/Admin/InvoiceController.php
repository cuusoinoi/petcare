<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\InvoiceModel;
use App\Models\InvoiceDetailModel;
use App\Models\CustomerModel;
use App\Models\PetModel;
use App\Models\ServiceTypeModel;

class InvoiceController extends BaseController
{
    protected $invoiceModel;
    protected $invoiceDetailModel;
    protected $customerModel;
    protected $petModel;
    protected $serviceTypeModel;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceDetailModel = new InvoiceDetailModel();
        $this->customerModel = new CustomerModel();
        $this->petModel = new PetModel();
        $this->serviceTypeModel = new ServiceTypeModel();
    }

    /**
     * List all invoices
     */
    public function index()
    {
        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $invoices = $this->invoiceModel->getInvoicesPaginated($limit, $offset);
        $totalInvoices = $this->invoiceModel->countAllResults();
        $totalPages = ceil($totalInvoices / $limit);

        // Get related data
        foreach ($invoices as &$invoice) {
            $customer = $this->customerModel->find($invoice['customer_id']);
            $pet = $this->petModel->find($invoice['pet_id']);
            $invoice['customer_name'] = $customer['customer_name'] ?? '';
            $invoice['pet_name'] = $pet['pet_name'] ?? '';
        }

        $data = [
            'title' => 'Danh sách hóa đơn - UIT Petcare',
            'invoices' => $invoices,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalInvoices' => $totalInvoices
        ];

        return view('admin/invoice/invoices', $data);
    }

    /**
     * Show add invoice form
     */
    public function add()
    {
        $customers = $this->customerModel->findAll();
        $pets = $this->petModel->findAll();
        $serviceTypes = $this->serviceTypeModel->findAll();

        $data = [
            'title' => 'Tạo hóa đơn - UIT Petcare',
            'customers' => $customers,
            'pets' => $pets,
            'serviceTypes' => $serviceTypes
        ];

        return view('admin/invoice/add_invoice', $data);
    }

    /**
     * Handle add invoice
     */
    public function store()
    {
        $customer_id = $this->request->getPost('customer_id');
        $pet_id = $this->request->getPost('pet_id');
        $invoice_date = $this->request->getPost('invoice_date') ?? date('Y-m-d');
        $discount = (int)$this->request->getPost('discount') ?: 0;
        $subtotal = (int)$this->request->getPost('subtotal') ?: 0;
        $deposit = (int)$this->request->getPost('deposit') ?: 0;
        $total_amount = (int)$this->request->getPost('total_amount') ?: 0;

        if (empty($customer_id) || empty($pet_id)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/invoices/add');
        }

        $data = [
            'customer_id' => $customer_id,
            'pet_id' => $pet_id,
            'pet_enclosure_id' => null,
            'invoice_date' => $invoice_date,
            'discount' => $discount,
            'subtotal' => $subtotal,
            'deposit' => $deposit,
            'total_amount' => $total_amount
        ];

        $invoiceId = $this->invoiceModel->insert($data);

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

            session()->setFlashdata('success', 'Tạo hóa đơn thành công!');
            return redirect()->to('/admin/invoices');
        } else {
            session()->setFlashdata('error', 'Không thể tạo hóa đơn. Vui lòng thử lại.');
            return redirect()->to('/admin/invoices/add');
        }
    }

    /**
     * Show edit invoice form
     */
    public function edit($id)
    {
        $invoice = $this->invoiceModel->find($id);

        if (!$invoice) {
            session()->setFlashdata('error', 'Không tìm thấy hóa đơn!');
            return redirect()->to('/admin/invoices');
        }

        $customers = $this->customerModel->findAll();
        $pets = $this->petModel->findAll();
        $serviceTypes = $this->serviceTypeModel->findAll();
        $invoiceDetails = $this->invoiceDetailModel->getInvoiceDetailsByInvoiceId($id);

        $data = [
            'title' => 'Chỉnh sửa hóa đơn - UIT Petcare',
            'invoice' => $invoice,
            'customers' => $customers,
            'pets' => $pets,
            'serviceTypes' => $serviceTypes,
            'invoiceDetails' => $invoiceDetails
        ];

        return view('admin/invoice/edit_invoice', $data);
    }

    /**
     * Handle update invoice
     */
    public function update($id)
    {
        $invoice = $this->invoiceModel->find($id);

        if (!$invoice) {
            session()->setFlashdata('error', 'Không tìm thấy hóa đơn!');
            return redirect()->to('/admin/invoices');
        }

        $customer_id = $this->request->getPost('customer_id');
        $pet_id = $this->request->getPost('pet_id');
        $invoice_date = $this->request->getPost('invoice_date') ?? date('Y-m-d');
        $discount = (int)$this->request->getPost('discount') ?: 0;
        $subtotal = (int)$this->request->getPost('subtotal') ?: 0;
        $deposit = (int)$this->request->getPost('deposit') ?: 0;
        $total_amount = (int)$this->request->getPost('total_amount') ?: 0;

        if (empty($customer_id) || empty($pet_id)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/invoices/edit/' . $id);
        }

        $data = [
            'customer_id' => $customer_id,
            'pet_id' => $pet_id,
            'invoice_date' => $invoice_date,
            'discount' => $discount,
            'subtotal' => $subtotal,
            'deposit' => $deposit,
            'total_amount' => $total_amount
        ];

        if ($this->invoiceModel->update($id, $data)) {
            // Delete old invoice details
            $this->invoiceDetailModel->where('invoice_id', $id)->delete();

            // Add new invoice details
            $serviceIds = $this->request->getPost('service_ids') ?? [];
            $quantities = $this->request->getPost('quantities') ?? [];
            $unitPrices = $this->request->getPost('unit_prices') ?? [];
            $totalPrices = $this->request->getPost('total_prices') ?? [];

            for ($i = 0; $i < count($serviceIds); $i++) {
                if (!empty($serviceIds[$i])) {
                    $this->invoiceDetailModel->insert([
                        'invoice_id' => $id,
                        'service_type_id' => $serviceIds[$i],
                        'quantity' => $quantities[$i] ?? 1,
                        'unit_price' => $unitPrices[$i] ?? 0,
                        'total_price' => $totalPrices[$i] ?? 0
                    ]);
                }
            }

            session()->setFlashdata('success', 'Cập nhật hóa đơn thành công!');
            return redirect()->to('/admin/invoices');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật hóa đơn. Vui lòng thử lại.');
            return redirect()->to('/admin/invoices/edit/' . $id);
        }
    }

    /**
     * Handle delete invoice
     */
    public function delete($id)
    {
        $invoice = $this->invoiceModel->find($id);

        if (!$invoice) {
            session()->setFlashdata('error', 'Không tìm thấy hóa đơn!');
            return redirect()->to('/admin/invoices');
        }

        // Delete invoice details first
        $this->invoiceDetailModel->where('invoice_id', $id)->delete();

        if ($this->invoiceModel->delete($id)) {
            session()->setFlashdata('success', 'Xóa hóa đơn thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa hóa đơn. Vui lòng thử lại.');
        }

        return redirect()->to('/admin/invoices');
    }
}
