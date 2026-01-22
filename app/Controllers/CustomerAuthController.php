<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CustomerModel;
use CodeIgniter\HTTP\ResponseInterface;

class CustomerAuthController extends BaseController
{
    protected $userModel;
    protected $customerModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->customerModel = new CustomerModel();
    }

    /**
     * Trang đăng nhập khách hàng
     */
    public function login()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $phone = $this->request->getPost('phone');
            $otp = $this->request->getPost('otp');

            if (empty($phone)) {
                return redirect()->back()->with('error', 'Vui lòng nhập số điện thoại');
            }

            // Kiểm tra OTP (mặc định 123456)
            $defaultOTP = '123456';
            if ($otp !== $defaultOTP) {
                return redirect()->back()->with('error', 'Mã OTP không đúng. Mã mặc định: 123456');
            }

            // Tìm khách hàng theo SĐT
            $customer = $this->customerModel->where('customer_phone_number', $phone)->first();

            if (!$customer) {
                return redirect()->back()->with('error', 'Số điện thoại chưa được đăng ký');
            }

            // Tạo hoặc cập nhật user với role customer
            $user = $this->userModel->where('username', $phone)->first();
            if (!$user) {
                $userData = [
                    'username' => $phone,
                    'password' => md5($defaultOTP), // Lưu OTP làm password
                    'fullname' => $customer['customer_name'],
                    'role' => 'customer'
                ];
                $userId = $this->userModel->insert($userData);
                
                if (!$userId) {
                    $errors = $this->userModel->errors();
                    return redirect()->back()->with('error', 'Lỗi khi tạo tài khoản: ' . implode(', ', $errors));
                }
                
                $user = $this->userModel->find($userId);
            } else {
                // Cập nhật role nếu chưa phải customer
                if ($user['role'] !== 'customer') {
                    $this->userModel->update($user['id'], ['role' => 'customer']);
                    $user = $this->userModel->find($user['id']);
                }
            }

            // Lưu session
            session()->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'fullname' => $user['fullname'],
                'role' => 'customer',
                'customer_id' => $customer['customer_id']
            ]);

            return redirect()->to('/customer/dashboard')->with('success', 'Đăng nhập thành công!');
        }

        $data = [
            'title' => 'Đăng nhập - UIT Petcare'
        ];

        return view('customer/auth/login', $data);
    }

    /**
     * Trang đăng ký khách hàng
     */
    public function register()
    {
        $method = $this->request->getMethod();
        log_message('debug', 'Register method: ' . $method);
        
        if (strtolower($method) === 'post') {
            $name = trim($this->request->getPost('name') ?? '');
            $phone = trim($this->request->getPost('phone') ?? '');
            $email = trim($this->request->getPost('email') ?? '');
            $address = trim($this->request->getPost('address') ?? '');
            $otp = trim($this->request->getPost('otp') ?? '');
            
            log_message('debug', 'Register data: name=' . $name . ', phone=' . $phone . ', otp=' . $otp);

            // Validation
            if (empty($name) || empty($phone)) {
                session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc');
                return redirect()->to('/customer/register');
            }

            // Kiểm tra OTP
            $defaultOTP = '123456';
            if ($otp !== $defaultOTP) {
                session()->setFlashdata('error', 'Mã OTP không đúng. Mã mặc định: 123456');
                return redirect()->to('/customer/register');
            }

            // Kiểm tra SĐT đã tồn tại
            $existingCustomer = $this->customerModel->where('customer_phone_number', $phone)->first();
            if ($existingCustomer) {
                session()->setFlashdata('error', 'Số điện thoại đã được đăng ký. Vui lòng đăng nhập hoặc sử dụng số điện thoại khác');
                return redirect()->to('/customer/register');
            }

            // Kiểm tra user đã tồn tại
            $existingUser = $this->userModel->where('username', $phone)->first();
            if ($existingUser) {
                session()->setFlashdata('error', 'Số điện thoại đã được sử dụng. Vui lòng đăng nhập');
                return redirect()->to('/customer/register');
            }

            // Tạo khách hàng mới
            $customerData = [
                'customer_name' => $name,
                'customer_phone_number' => $phone,
                'customer_email' => !empty($email) ? $email : null,
                'customer_address' => !empty($address) ? $address : null
            ];

            try {
                // Tắt validation tạm thời để debug
                $this->customerModel->skipValidation(true);
                $customerId = $this->customerModel->insert($customerData);
                
                log_message('debug', 'Customer insert result: ' . ($customerId ? $customerId : 'false'));
                
                if (!$customerId) {
                    $errors = $this->customerModel->errors();
                    $errorMsg = !empty($errors) ? implode(', ', $errors) : 'Không thể tạo tài khoản khách hàng';
                    log_message('error', 'Customer insert failed: ' . $errorMsg);
                    log_message('error', 'Customer data: ' . json_encode($customerData));
                    session()->setFlashdata('error', 'Lỗi: ' . $errorMsg);
                    return redirect()->to('/customer/register');
                }

                // Tạo user account
                $userData = [
                    'username' => $phone,
                    'password' => md5($defaultOTP),
                    'fullname' => $name,
                    'role' => 'customer'
                ];

                // Tắt validation tạm thời để debug
                $this->userModel->skipValidation(true);
                $userId = $this->userModel->insert($userData);
                
                log_message('debug', 'User insert result: ' . ($userId ? $userId : 'false'));
                
                if (!$userId) {
                    // Xóa customer đã tạo nếu user insert thất bại
                    $this->customerModel->delete($customerId);
                    $errors = $this->userModel->errors();
                    $errorMsg = !empty($errors) ? implode(', ', $errors) : 'Không thể tạo tài khoản người dùng';
                    log_message('error', 'User insert failed: ' . $errorMsg);
                    log_message('error', 'User data: ' . json_encode($userData));
                    session()->setFlashdata('error', 'Lỗi: ' . $errorMsg);
                    return redirect()->to('/customer/register');
                }

                // Lưu session
                session()->set([
                    'user_id' => $userId,
                    'username' => $phone,
                    'fullname' => $name,
                    'role' => 'customer',
                    'customer_id' => $customerId
                ]);

                log_message('debug', 'Registration successful, redirecting to dashboard');
                return redirect()->to('/customer/dashboard')->with('success', 'Đăng ký thành công!');
            } catch (\Exception $e) {
                log_message('error', 'Register exception: ' . $e->getMessage());
                log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                session()->setFlashdata('error', 'Lỗi hệ thống: ' . $e->getMessage());
                return redirect()->to('/customer/register');
            }
        }

        $data = [
            'title' => 'Đăng ký - UIT Petcare'
        ];

        return view('customer/auth/register', $data);
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/customer/login')->with('success', 'Đăng xuất thành công!');
    }
}
