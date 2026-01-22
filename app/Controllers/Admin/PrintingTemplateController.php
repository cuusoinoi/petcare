<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\InvoiceModel;
use App\Models\InvoiceDetailModel;
use App\Models\CustomerModel;
use App\Models\PetModel;
use App\Models\PetEnclosureModel;
use App\Models\ServiceTypeModel;
use App\Models\GeneralSettingModel;

class PrintingTemplateController extends BaseController
{
    protected $invoiceModel;
    protected $invoiceDetailModel;
    protected $customerModel;
    protected $petModel;
    protected $petEnclosureModel;
    protected $serviceTypeModel;
    protected $settingModel;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceDetailModel = new InvoiceDetailModel();
        $this->customerModel = new CustomerModel();
        $this->petModel = new PetModel();
        $this->petEnclosureModel = new PetEnclosureModel();
        $this->serviceTypeModel = new ServiceTypeModel();
        $this->settingModel = new GeneralSettingModel();
    }

    /**
     * Printing template page
     */
    public function index()
    {
        $invoices = $this->invoiceModel->orderBy('invoice_id', 'DESC')->findAll();

        // Enrich invoice data
        foreach ($invoices as &$inv) {
            $customer = $this->customerModel->find($inv['customer_id']);
            $pet = $this->petModel->find($inv['pet_id']);
            $inv['customer_name'] = $customer['customer_name'] ?? '';
            $inv['pet_name'] = $pet['pet_name'] ?? '';
        }

        $invoiceId = $this->request->getGet('invoice_id') ? intval($this->request->getGet('invoice_id')) : 0;
        $petEnclosureId = $this->request->getGet('pet_enclosure_id') ? intval($this->request->getGet('pet_enclosure_id')) : 0;

        $mappedInvoiceId = null;
        if ($invoiceId > 0) {
            $mappedInvoiceId = $invoiceId;
        } elseif ($petEnclosureId > 0) {
            $invoice = $this->invoiceModel->where('pet_enclosure_id', $petEnclosureId)->first();
            $mappedInvoiceId = $invoice['invoice_id'] ?? null;
        }

        $data = [
            'title' => 'Mẫu in hóa đơn - UIT Petcare',
            'invoices' => $invoices,
            'mappedInvoiceId' => $mappedInvoiceId,
            'invoiceId' => $invoiceId,
            'petEnclosureId' => $petEnclosureId
        ];

        return view('admin/printing_template/printing_template', $data);
    }

    /**
     * Load Commitment (Giấy cam kết lưu chuồng) via AJAX
     */
    public function loadCommit($id)
    {
        $invoice = $this->invoiceModel->find($id);
        if (!$invoice) {
            return $this->response->setBody('Không tìm thấy hóa đơn');
        }

        $customer = $this->customerModel->find($invoice['customer_id']);
        $pet = $this->petModel->find($invoice['pet_id']);
        $enclosure = $this->petEnclosureModel->find($invoice['pet_enclosure_id']);
        $settings = $this->settingModel->getSettings();

        $data = [
            'invoice' => $invoice,
            'customer' => $customer,
            'pet' => $pet,
            'enclosure' => $enclosure,
            'settings' => $settings
        ];

        return view('admin/printing_template/load_commit', $data);
    }

    /**
     * Load Invoice via AJAX
     */
    public function loadInvoice($id)
    {
        $invoice = $this->invoiceModel->find($id);
        if (!$invoice) {
            return $this->response->setBody('Không tìm thấy hóa đơn');
        }

        $customer = $this->customerModel->find($invoice['customer_id']);
        $pet = $this->petModel->find($invoice['pet_id']);
        $details = $this->invoiceDetailModel->getDetailsByInvoiceId($id);
        $settings = $this->settingModel->getSettings();

        // Get service names for details
        foreach ($details as &$d) {
            $service = $this->serviceTypeModel->find($d['service_type_id']);
            $d['service_name'] = $service['service_name'] ?? '';
        }

        $data = [
            'invoice' => $invoice,
            'customer' => $customer,
            'pet' => $pet,
            'details' => $details,
            'settings' => $settings
        ];

        return view('admin/printing_template/load_invoice', $data);
    }
}
