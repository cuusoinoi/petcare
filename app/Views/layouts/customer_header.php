<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'UIT Petcare') ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('admin_assets/images/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/customer.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <nav class="customer-nav">
        <div class="container">
            <div class="nav-content">
                <a href="<?= site_url('customer') ?>" class="nav-logo">
                    <img src="<?= base_url('admin_assets/images/logo.png') ?>" alt="UIT Petcare" class="logo-img">
                    <span class="logo-text">UIT Petcare</span>
                </a>
                <ul class="nav-menu">
                    <?php 
                    $currentUri = uri_string();
                    $isHome = ($currentUri === 'customer' || $currentUri === '' || strpos($currentUri, 'customer') === false);
                    ?>
                    <li><a href="<?= site_url('customer') ?>" class="<?= $isHome ? 'active' : '' ?>">Trang chủ</a></li>
                    <li><a href="<?= site_url('customer/services') ?>" class="<?= strpos($currentUri, 'customer/services') !== false ? 'active' : '' ?>">Dịch vụ</a></li>
                    <li><a href="<?= site_url('customer/contact') ?>" class="<?= strpos($currentUri, 'customer/contact') !== false ? 'active' : '' ?>">Liên hệ</a></li>
                    <?php if (session()->get('role') === 'customer'): ?>
                        <li><a href="<?= site_url('customer/booking') ?>" class="<?= strpos($currentUri, 'customer/booking') !== false ? 'active' : '' ?>">Đặt lịch</a></li>
                        <li class="nav-user-dropdown">
                            <a href="#" class="nav-user-toggle">
                                <i class="fas fa-user-circle"></i> 
                                <span><?= esc(session()->get('fullname') ?? 'Tài khoản') ?></span>
                                <i class="fas fa-chevron-down" style="font-size: 0.8rem; margin-left: 0.5rem;"></i>
                            </a>
                            <ul class="nav-user-menu">
                                <li><a href="<?= site_url('customer/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                                <li><a href="<?= site_url('customer/dashboard/profile') ?>"><i class="fas fa-user"></i> Thông tin cá nhân</a></li>
                                <li><a href="<?= site_url('customer/dashboard/pets') ?>"><i class="fas fa-paw"></i> Thú cưng</a></li>
                                <li><hr style="margin: 0.5rem 0; border: none; border-top: 1px solid #e0e0e0;"></li>
                                <li><a href="<?= site_url('customer/logout') ?>" style="color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="<?= site_url('customer/login') ?>" class="btn-login">Đăng nhập</a></li>
                        <li><a href="<?= site_url('customer/register') ?>" class="btn-register">Đăng ký</a></li>
                    <?php endif; ?>
                </ul>
                <button class="nav-toggle" id="navToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>
