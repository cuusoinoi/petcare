<?php
$currentUrl = current_url();
$currentUri = uri_string();

function isMenuActive($path, $currentUri) {
    return str_contains($currentUri, $path);
}

function isSubmenuActive($path, $currentUri) {
    // Exact match or starts with path
    return $currentUri === $path || str_starts_with($currentUri, $path . '/');
}

function submenuActiveClass($path, $currentUri) {
    return isSubmenuActive($path, $currentUri) ? 'submenu-active' : '';
}
?>
<aside class="sidebar" id="sidebar">
    <a href="<?= site_url('admin/dashboard') ?>" class="sidebar__logo">
        <div class="sidebar__logo-icon">
            <img src="<?= base_url('admin_assets/images/logo.png') ?>" alt="UIT Petcare Logo" class="sidebar__logo-img">
        </div>
        <div class="sidebar__logo-text">UIT Petcare</div>
    </a>
    <nav class="sidebar__menu">
        <div class="sidebar__item <?= isMenuActive('admin/dashboard', $currentUri) ? 'sidebar__item--active' : '' ?>">
            <a href="<?= site_url('admin/dashboard') ?>" class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-chart-line"></i></span>
                <span class="sidebar__text">Tổng quan</span>
            </a>
        </div>

        <!-- Nhóm 1: Quản lý chính -->
        <div class="sidebar__group">
            <div class="sidebar__group-title">Quản lý chính</div>

            <div class="sidebar__item sidebar__item--has-submenu <?= isMenuActive('admin/customers', $currentUri) ? 'sidebar__item--active sidebar__item--open' : '' ?>">
                <div class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-users"></i></span>
                    <span class="sidebar__text">Khách hàng</span>
                    <span class="sidebar__arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </div>
                <div class="sidebar__submenu">
                    <a href="<?= site_url('admin/customers') ?>" class="<?= submenuActiveClass('admin/customers', $currentUri) && !str_contains($currentUri, '/add') ? 'submenu-active' : '' ?>">Danh sách khách hàng</a>
                    <a href="<?= site_url('admin/customers/add') ?>" class="<?= submenuActiveClass('admin/customers/add', $currentUri) ?>">Thêm khách hàng</a>
                </div>
                <div class="sidebar__submenu-popup"></div>
            </div>

            <div class="sidebar__item sidebar__item--has-submenu <?= isMenuActive('admin/pets', $currentUri) ? 'sidebar__item--active sidebar__item--open' : '' ?>">
                <div class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-dog"></i></span>
                    <span class="sidebar__text">Thú cưng</span>
                    <span class="sidebar__arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </div>
                <div class="sidebar__submenu">
                    <a href="<?= site_url('admin/pets') ?>" class="<?= submenuActiveClass('admin/pets', $currentUri) && !str_contains($currentUri, '/add') ? 'submenu-active' : '' ?>">Danh sách thú cưng</a>
                    <a href="<?= site_url('admin/pets/add') ?>" class="<?= submenuActiveClass('admin/pets/add', $currentUri) ?>">Thêm thú cưng</a>
                </div>
                <div class="sidebar__submenu-popup"></div>
            </div>

            <div class="sidebar__item sidebar__item--has-submenu <?= isMenuActive('admin/doctors', $currentUri) ? 'sidebar__item--active sidebar__item--open' : '' ?>">
                <div class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-user-doctor"></i></span>
                    <span class="sidebar__text">Bác sĩ</span>
                    <span class="sidebar__arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </div>
                <div class="sidebar__submenu">
                    <a href="<?= site_url('admin/doctors') ?>" class="<?= submenuActiveClass('admin/doctors', $currentUri) && !str_contains($currentUri, '/add') ? 'submenu-active' : '' ?>">Danh sách bác sĩ</a>
                    <a href="<?= site_url('admin/doctors/add') ?>" class="<?= submenuActiveClass('admin/doctors/add', $currentUri) ?>">Thêm bác sĩ</a>
                </div>
                <div class="sidebar__submenu-popup"></div>
            </div>
        </div>

        <!-- Nhóm 2: Khám & Điều trị -->
        <div class="sidebar__group">
            <div class="sidebar__group-title">Khám & Điều trị</div>

            <div class="sidebar__item <?= isMenuActive('admin/appointments', $currentUri) ? 'sidebar__item--active' : '' ?>">
                <a href="<?= site_url('admin/appointments') ?>" class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-calendar-check"></i></span>
                    <span class="sidebar__text">Lịch hẹn</span>
                </a>
            </div>

            <div class="sidebar__item sidebar__item--has-submenu <?= isMenuActive('admin/medical-records', $currentUri) ? 'sidebar__item--active sidebar__item--open' : '' ?>">
                <div class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-stethoscope"></i></span>
                    <span class="sidebar__text">Khám bệnh</span>
                    <span class="sidebar__arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </div>
                <div class="sidebar__submenu">
                    <a href="<?= site_url('admin/medical-records') ?>" class="<?= submenuActiveClass('admin/medical-records', $currentUri) && !str_contains($currentUri, '/add') ? 'submenu-active' : '' ?>">Lịch sử khám</a>
                    <a href="<?= site_url('admin/medical-records/add') ?>" class="<?= submenuActiveClass('admin/medical-records/add', $currentUri) ?>">Tạo phiếu khám</a>
                </div>
                <div class="sidebar__submenu-popup"></div>
            </div>

            <div class="sidebar__item sidebar__item--has-submenu <?= isMenuActive('admin/treatment-courses', $currentUri) ? 'sidebar__item--active sidebar__item--open' : '' ?>">
                <div class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-notes-medical"></i></span>
                    <span class="sidebar__text">Liệu trình</span>
                    <span class="sidebar__arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </div>
                <div class="sidebar__submenu">
                    <a href="<?= site_url('admin/treatment-courses') ?>" class="<?= submenuActiveClass('admin/treatment-courses', $currentUri) && !str_contains($currentUri, '/add') ? 'submenu-active' : '' ?>">Danh sách liệu trình</a>
                    <a href="<?= site_url('admin/treatment-courses/add') ?>" class="<?= submenuActiveClass('admin/treatment-courses/add', $currentUri) ?>">Thêm liệu trình</a>
                </div>
                <div class="sidebar__submenu-popup"></div>
            </div>

            <div class="sidebar__item sidebar__item--has-submenu <?= isMenuActive('admin/pet-vaccinations', $currentUri) ? 'sidebar__item--active sidebar__item--open' : '' ?>">
                <div class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-syringe"></i></span>
                    <span class="sidebar__text">Tiêm chủng</span>
                    <span class="sidebar__arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </div>
                <div class="sidebar__submenu">
                    <a href="<?= site_url('admin/pet-vaccinations') ?>" class="<?= submenuActiveClass('admin/pet-vaccinations', $currentUri) && !str_contains($currentUri, '/add') ? 'submenu-active' : '' ?>">Lịch sử tiêm chủng</a>
                    <a href="<?= site_url('admin/pet-vaccinations/add') ?>" class="<?= submenuActiveClass('admin/pet-vaccinations/add', $currentUri) ?>">Thêm tiêm chủng</a>
                </div>
                <div class="sidebar__submenu-popup"></div>
            </div>
        </div>

        <!-- Nhóm 3: Lưu chuồng -->
        <div class="sidebar__group">
            <div class="sidebar__group-title">Lưu chuồng</div>

            <div class="sidebar__item sidebar__item--has-submenu <?= isMenuActive('admin/pet-enclosures', $currentUri) ? 'sidebar__item--active sidebar__item--open' : '' ?>">
                <div class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-house"></i></span>
                    <span class="sidebar__text">Lưu chuồng</span>
                    <span class="sidebar__arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </div>
                <div class="sidebar__submenu">
                    <a href="<?= site_url('admin/pet-enclosures') ?>" class="<?= submenuActiveClass('admin/pet-enclosures', $currentUri) && !str_contains($currentUri, '/add') ? 'submenu-active' : '' ?>">Danh sách chuồng</a>
                    <a href="<?= site_url('admin/pet-enclosures/add') ?>" class="<?= submenuActiveClass('admin/pet-enclosures/add', $currentUri) ?>">Thêm chuồng thú</a>
                </div>
                <div class="sidebar__submenu-popup"></div>
            </div>
        </div>

        <!-- Nhóm 4: Hóa đơn -->
        <div class="sidebar__group">
            <div class="sidebar__group-title">Hóa đơn</div>

            <div class="sidebar__item sidebar__item--has-submenu <?= isMenuActive('admin/invoices', $currentUri) || isMenuActive('admin/printing-template', $currentUri) ? 'sidebar__item--active sidebar__item--open' : '' ?>">
                <div class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-file-invoice"></i></span>
                    <span class="sidebar__text">Hóa đơn</span>
                    <span class="sidebar__arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </div>
                <div class="sidebar__submenu">
                    <a href="<?= site_url('admin/invoices') ?>" class="<?= submenuActiveClass('admin/invoices', $currentUri) ? 'submenu-active' : '' ?>">Danh sách hóa đơn</a>
                    <a href="<?= site_url('admin/printing-template') ?>" class="<?= submenuActiveClass('admin/printing-template', $currentUri) ? 'submenu-active' : '' ?>">Mẫu in hóa đơn</a>
                </div>
                <div class="sidebar__submenu-popup"></div>
            </div>
        </div>

        <!-- Nhóm 5: Danh mục -->
        <div class="sidebar__group">
            <div class="sidebar__group-title">Danh mục</div>

            <div class="sidebar__item sidebar__item--has-submenu <?= isMenuActive('admin/service-types', $currentUri) ? 'sidebar__item--active sidebar__item--open' : '' ?>">
                <div class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-hands-holding-circle"></i></span>
                    <span class="sidebar__text">Dịch vụ</span>
                    <span class="sidebar__arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </div>
                <div class="sidebar__submenu">
                    <a href="<?= site_url('admin/service-types') ?>" class="<?= submenuActiveClass('admin/service-types', $currentUri) && !str_contains($currentUri, '/add') ? 'submenu-active' : '' ?>">Danh sách dịch vụ</a>
                    <a href="<?= site_url('admin/service-types/add') ?>" class="<?= submenuActiveClass('admin/service-types/add', $currentUri) ?>">Thêm dịch vụ</a>
                </div>
                <div class="sidebar__submenu-popup"></div>
            </div>

            <div class="sidebar__item sidebar__item--has-submenu <?= isMenuActive('admin/medicines', $currentUri) ? 'sidebar__item--active sidebar__item--open' : '' ?>">
                <div class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-pills"></i></span>
                    <span class="sidebar__text">Thuốc</span>
                    <span class="sidebar__arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </div>
                <div class="sidebar__submenu">
                    <a href="<?= site_url('admin/medicines') ?>" class="<?= submenuActiveClass('admin/medicines', $currentUri) && !str_contains($currentUri, '/add') ? 'submenu-active' : '' ?>">Danh sách thuốc</a>
                    <a href="<?= site_url('admin/medicines/add') ?>" class="<?= submenuActiveClass('admin/medicines/add', $currentUri) ?>">Thêm thuốc</a>
                </div>
                <div class="sidebar__submenu-popup"></div>
            </div>

            <div class="sidebar__item sidebar__item--has-submenu <?= isMenuActive('admin/vaccines', $currentUri) ? 'sidebar__item--active sidebar__item--open' : '' ?>">
                <div class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-vial"></i></span>
                    <span class="sidebar__text">Vaccine</span>
                    <span class="sidebar__arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </div>
                <div class="sidebar__submenu">
                    <a href="<?= site_url('admin/vaccines') ?>" class="<?= submenuActiveClass('admin/vaccines', $currentUri) && !str_contains($currentUri, '/add') ? 'submenu-active' : '' ?>">Danh sách vaccine</a>
                    <a href="<?= site_url('admin/vaccines/add') ?>" class="<?= submenuActiveClass('admin/vaccines/add', $currentUri) ?>">Thêm vaccine</a>
                </div>
                <div class="sidebar__submenu-popup"></div>
            </div>
        </div>

        <!-- Nhóm 6: Hệ thống -->
        <div class="sidebar__group">
            <div class="sidebar__group-title">Hệ thống</div>

            <div class="sidebar__item sidebar__item--has-submenu <?= isMenuActive('admin/users', $currentUri) ? 'sidebar__item--active sidebar__item--open' : '' ?>">
                <div class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-circle-user"></i></span>
                    <span class="sidebar__text">Người dùng</span>
                    <span class="sidebar__arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </div>
                <div class="sidebar__submenu">
                    <a href="<?= site_url('admin/users') ?>" class="<?= submenuActiveClass('admin/users', $currentUri) && !str_contains($currentUri, '/add') && !str_contains($currentUri, 'change-password') ? 'submenu-active' : '' ?>">Danh sách người dùng</a>
                    <a href="<?= site_url('admin/users/add') ?>" class="<?= submenuActiveClass('admin/users/add', $currentUri) ?>">Thêm người dùng</a>
                    <a href="<?= site_url('admin/users/change-password') ?>" class="<?= submenuActiveClass('admin/users/change-password', $currentUri) ?>">Đổi mật khẩu</a>
                </div>
                <div class="sidebar__submenu-popup"></div>
            </div>

            <div class="sidebar__item <?= isMenuActive('admin/settings', $currentUri) ? 'sidebar__item--active' : '' ?>">
                <a href="<?= site_url('admin/settings') ?>" class="sidebar__link">
                    <span class="sidebar__icon"><i class="fa-solid fa-gear"></i></span>
                    <span class="sidebar__text">Cài đặt</span>
                </a>
            </div>
        </div>
    </nav>
</aside>
