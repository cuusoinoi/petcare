<?php
use App\Models\GeneralSettingModel;
use App\Models\UserModel;

$settingModel = new GeneralSettingModel();
$userModel = new UserModel();
$settings = $settingModel->getSettings();
$currentUser = $userModel->getUserByUsername(session()->get('username'));
?>
<header class="topbar">
    <div class="topbar__left">
        <button class="topbar__toggle" id="toggleSidebar"><i class="fas fa-bars"></i></button>
        <div class="topbar__clinic-info">
            <div class="clinic-name"><?= esc($settings['clinic_name'] ?? 'Phòng khám thú y') ?></div>
            <div class="clinic-details">
                <?= esc($settings['clinic_address_1'] ?? '') ?>
                <?= isset($settings['clinic_address_2']) && $settings['clinic_address_2'] ? ', ' . esc($settings['clinic_address_2']) : '' ?>
                <?= isset($settings['phone_number_1']) && $settings['phone_number_1'] ? ' | ' . esc($settings['phone_number_1']) : '' ?>
                <?= isset($settings['phone_number_2']) && $settings['phone_number_2'] ? ' - ' . esc($settings['phone_number_2']) : '' ?>
            </div>
        </div>
    </div>
    <div class="topbar__actions">
        <a href="<?= site_url('customer') ?>" target="_blank" class="btn-customer-link" title="Xem trang Customer">
            <i class="fas fa-external-link-alt"></i>
            <span>Customer</span>
        </a>
        <div class="topbar__user">
            <div class="topbar__user-info">
                <img src="<?= base_url('admin_assets/images/' . ($currentUser['avatar'] ?? 'default-avatar.jpg')) ?>" alt="avatar" class="topbar__avatar" />
                <span class="topbar__username"><?= esc(session()->get('fullname')) ?></span>
            </div>
            <div class="topbar__dropdown">
                <a href="<?= site_url('admin/users/edit/' . $currentUser['id']) ?>" class="topbar__myaccount">
                    <i class="fa-solid fa-circle-user"></i> Thông tin tài khoản
                </a>
                <a href="<?= site_url('admin/users/change-password') ?>" class="topbar__changepassword">
                    <i class="fa-solid fas fa-key"></i> Đổi mật khẩu
                </a>
                <button id="themeToggle" class="topbar__theme-toggle" title="Chuyển giao diện">
                    <i class="fa-solid fa-moon"></i> <span id="themeToggleText">Giao diện tối</span>
                </button>
                <a href="<?= site_url('admin/logout') ?>" class="topbar__logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>
</header>
