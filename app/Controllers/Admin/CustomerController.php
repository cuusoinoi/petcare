<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\CustomerModel;

class CustomerController extends BaseController
{
    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    /**
     * List all customers
     */
    public function index()
    {
        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $customers = $this->customerModel->getCustomersPaginated($limit, $offset);
        $totalCustomers = $this->customerModel->getCustomerCount();
        $totalPages = ceil($totalCustomers / $limit);

        $data = [
            'title' => 'Danh sách khách hàng - UIT Petcare',
            'customers' => $customers,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCustomers' => $totalCustomers
        ];

        return view('admin/customer/customers', $data);
    }

    /**
     * Show add customer form
     */
    public function add()
    {
        $data = [
            'title' => 'Thêm khách hàng - UIT Petcare'
        ];

        return view('admin/customer/add_customer', $data);
    }

    /**
     * Handle add customer
     */
    public function store()
    {
        $fullname = trim($this->request->getPost('fullname'));
        $phone = trim($this->request->getPost('phone'));
        $identity_card = trim($this->request->getPost('identity_card')) ?: null;
        $address = trim($this->request->getPost('address'));
        $note = trim($this->request->getPost('note')) ?: null;

        if (empty($fullname) || empty($phone) || empty($address)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/customers/add');
        }

        $data = [
            'customer_name' => $fullname,
            'customer_phone_number' => $phone,
            'customer_identity_card' => $identity_card,
            'customer_address' => $address,
            'customer_note' => $note
        ];

        if ($this->customerModel->insert($data)) {
            session()->setFlashdata('success', 'Thêm khách hàng thành công!');
            return redirect()->to('/admin/customers');
        } else {
            session()->setFlashdata('error', 'Không thể thêm khách hàng. Vui lòng thử lại.');
            return redirect()->to('/admin/customers/add');
        }
    }

    /**
     * Show edit customer form
     */
    public function edit($id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            session()->setFlashdata('error', 'Không tìm thấy khách hàng!');
            return redirect()->to('/admin/customers');
        }

        $data = [
            'title' => 'Chỉnh sửa khách hàng - UIT Petcare',
            'customer' => $customer
        ];

        return view('admin/customer/edit_customer', $data);
    }

    /**
     * Handle update customer
     */
    public function update($id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            session()->setFlashdata('error', 'Không tìm thấy khách hàng!');
            return redirect()->to('/admin/customers');
        }

        $fullname = trim($this->request->getPost('fullname'));
        $phone = trim($this->request->getPost('phone'));
        $identity_card = trim($this->request->getPost('identity_card')) ?: null;
        $address = trim($this->request->getPost('address'));
        $note = trim($this->request->getPost('note')) ?: null;

        if (empty($fullname) || empty($phone) || empty($address)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/customers/edit/' . $id);
        }

        $data = [
            'customer_name' => $fullname,
            'customer_phone_number' => $phone,
            'customer_identity_card' => $identity_card,
            'customer_address' => $address,
            'customer_note' => $note
        ];

        if ($this->customerModel->update($id, $data)) {
            session()->setFlashdata('success', 'Cập nhật khách hàng thành công!');
            return redirect()->to('/admin/customers');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật khách hàng. Vui lòng thử lại.');
            return redirect()->to('/admin/customers/edit/' . $id);
        }
    }

    /**
     * Handle delete customer
     */
    public function delete($id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            session()->setFlashdata('error', 'Không tìm thấy khách hàng!');
            return redirect()->to('/admin/customers');
        }

        if ($this->customerModel->delete($id)) {
            session()->setFlashdata('success', 'Xóa khách hàng thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa khách hàng. Vui lòng thử lại.');
        }

        return redirect()->to('/admin/customers');
    }
}
