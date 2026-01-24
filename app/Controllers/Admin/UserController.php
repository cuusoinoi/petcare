<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * List all users
     */
    public function index()
    {
        $limit = 10;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $limit;

        $users = $this->userModel->orderBy('id', 'DESC')
                                 ->findAll($limit, $offset);
        $totalUsers = $this->userModel->countAllResults();
        $totalPages = ceil($totalUsers / $limit);

        $data = [
            'title' => 'Danh sách người dùng - UIT Petcare',
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalUsers' => $totalUsers
        ];

        return view('admin/user/users', $data);
    }

    /**
     * Show add user form
     */
    public function add()
    {
        $roles = $this->userModel->getAvailableRoles();
        
        // Map roles to labels
        $roleLabels = [
            'admin' => 'Quản trị viên',
            'staff' => 'Nhân viên',
            'customer' => 'Khách hàng',
            'doctor' => 'Bác sĩ'
        ];
        
        $data = [
            'title' => 'Thêm người dùng - UIT Petcare',
            'roles' => $roles,
            'roleLabels' => $roleLabels
        ];

        return view('admin/user/add_user', $data);
    }

    /**
     * Handle add user
     */
    public function store()
    {
        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');
        $fullname = trim($this->request->getPost('fullname'));
        $role = $this->request->getPost('role') ?? 'staff';
        $avatar = null;

        // Handle file upload
        $file = $this->request->getFile('avatar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/admin_assets/images/', $newName);
            $avatar = $newName;
        }

        if (empty($username) || empty($password) || empty($fullname)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/users/add');
        }

        // Check if username exists
        $existingUser = $this->userModel->getUserByUsername($username);
        if ($existingUser) {
            session()->setFlashdata('error', 'Tên đăng nhập đã tồn tại!');
            return redirect()->to('/admin/users/add');
        }

        $data = [
            'username' => $username,
            'password' => md5($password), // Using MD5 as in original code
            'fullname' => $fullname,
            'avatar' => $avatar,
            'role' => $role
        ];

        if ($this->userModel->insert($data)) {
            session()->setFlashdata('success', 'Thêm người dùng thành công!');
            return redirect()->to('/admin/users');
        } else {
            session()->setFlashdata('error', 'Không thể thêm người dùng. Vui lòng thử lại.');
            return redirect()->to('/admin/users/add');
        }
    }

    /**
     * Show edit user form
     */
    public function edit($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'Không tìm thấy người dùng!');
            return redirect()->to('/admin/users');
        }

        $roles = $this->userModel->getAvailableRoles();
        
        // Map roles to labels
        $roleLabels = [
            'admin' => 'Quản trị viên',
            'staff' => 'Nhân viên',
            'customer' => 'Khách hàng',
            'doctor' => 'Bác sĩ'
        ];
        
        $data = [
            'title' => 'Chỉnh sửa người dùng - UIT Petcare',
            'user' => $user,
            'roles' => $roles,
            'roleLabels' => $roleLabels
        ];

        return view('admin/user/edit_user', $data);
    }

    /**
     * Handle update user
     */
    public function update($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'Không tìm thấy người dùng!');
            return redirect()->to('/admin/users');
        }

        $username = trim($this->request->getPost('username'));
        $fullname = trim($this->request->getPost('fullname'));
        $role = $this->request->getPost('role') ?? 'staff';
        $avatar = $user['avatar'];

        // Handle file upload
        $file = $this->request->getFile('avatar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/admin_assets/images/', $newName);
            // Delete old avatar if exists
            if ($avatar && file_exists(ROOTPATH . 'public/admin_assets/images/' . $avatar)) {
                unlink(ROOTPATH . 'public/admin_assets/images/' . $avatar);
            }
            $avatar = $newName;
        }

        if (empty($username) || empty($fullname)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            return redirect()->to('/admin/users/edit/' . $id);
        }

        // Check if username exists (except current user)
        $existingUser = $this->userModel->getUserByUsername($username);
        if ($existingUser && $existingUser['id'] != $id) {
            session()->setFlashdata('error', 'Tên đăng nhập đã tồn tại!');
            return redirect()->to('/admin/users/edit/' . $id);
        }

        $data = [
            'username' => $username,
            'fullname' => $fullname,
            'avatar' => $avatar,
            'role' => $role
        ];

        if ($this->userModel->update($id, $data)) {
            session()->setFlashdata('success', 'Cập nhật người dùng thành công!');
            return redirect()->to('/admin/users');
        } else {
            session()->setFlashdata('error', 'Không thể cập nhật người dùng. Vui lòng thử lại.');
            return redirect()->to('/admin/users/edit/' . $id);
        }
    }

    /**
     * Handle delete user
     */
    public function delete($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'Không tìm thấy người dùng!');
            return redirect()->to('/admin/users');
        }

        // Don't allow deleting yourself
        if ($user['username'] === session()->get('username')) {
            session()->setFlashdata('error', 'Bạn không thể xóa chính mình!');
            return redirect()->to('/admin/users');
        }

        if ($this->userModel->delete($id)) {
            // Delete avatar if exists
            if ($user['avatar'] && file_exists(ROOTPATH . 'public/admin_assets/images/' . $user['avatar'])) {
                unlink(ROOTPATH . 'public/admin_assets/images/' . $user['avatar']);
            }
            session()->setFlashdata('success', 'Xóa người dùng thành công!');
        } else {
            session()->setFlashdata('error', 'Không thể xóa người dùng. Vui lòng thử lại.');
        }

        return redirect()->to('/admin/users');
    }

    /**
     * Show change password form
     */
    public function changePassword()
    {
        $currentUser = $this->userModel->getUserByUsername(session()->get('username'));
        
        $data = [
            'title' => 'Đổi mật khẩu - UIT Petcare',
            'user' => $currentUser
        ];

        return view('admin/user/change_password', $data);
    }

    /**
     * Handle update password
     */
    public function updatePassword()
    {
        $currentUser = $this->userModel->getUserByUsername(session()->get('username'));
        $id = $this->request->getPost('id');
        $oldPassword = $this->request->getPost('old_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            session()->setFlashdata('error', 'Vui lòng điền đầy đủ thông tin.');
            return redirect()->to('/admin/users/change-password');
        }

        // Verify old password
        if (md5($oldPassword) !== $currentUser['password']) {
            session()->setFlashdata('error', 'Mật khẩu cũ không đúng!');
            return redirect()->to('/admin/users/change-password');
        }

        if ($newPassword !== $confirmPassword) {
            session()->setFlashdata('error', 'Mật khẩu mới và xác nhận mật khẩu không khớp!');
            return redirect()->to('/admin/users/change-password');
        }

        $data = [
            'password' => md5($newPassword)
        ];

        if ($this->userModel->update($id, $data)) {
            session()->setFlashdata('success', 'Đổi mật khẩu thành công!');
            return redirect()->to('/admin/users/change-password');
        } else {
            session()->setFlashdata('error', 'Không thể đổi mật khẩu. Vui lòng thử lại.');
            return redirect()->to('/admin/users/change-password');
        }
    }
}
