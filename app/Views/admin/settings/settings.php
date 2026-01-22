<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('admin_assets/images/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/grid.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/responsive.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <div class="overlay" id="overlay"></div>
    <?= view('layouts/admin_sidebar') ?>
    <main class="main">
        <?= view('layouts/admin_header') ?>
        <main class="content">
            <div class="form-container">
                <h2>Cài đặt chung</h2>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <form action="<?= site_url('admin/settings/update') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="clinic_name">Tên phòng khám</label>
                        <input type="text" id="clinic_name" name="clinic_name" value="<?= esc($settings['clinic_name'] ?? 'UIT Petcare') ?>">
                    </div>
                    <div class="form-group">
                        <label for="clinic_address_1">Địa chỉ 1</label>
                        <input type="text" id="clinic_address_1" name="clinic_address_1" value="<?= esc($settings['clinic_address_1'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="clinic_address_2">Địa chỉ 2</label>
                        <input type="text" id="clinic_address_2" name="clinic_address_2" value="<?= esc($settings['clinic_address_2'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone_number_1">Số điện thoại 1</label>
                        <input type="text" id="phone_number_1" name="phone_number_1" value="<?= esc($settings['phone_number_1'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone_number_2">Số điện thoại 2</label>
                        <input type="text" id="phone_number_2" name="phone_number_2" value="<?= esc($settings['phone_number_2'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="representative_name">Tên người đại diện</label>
                        <input type="text" id="representative_name" name="representative_name" value="<?= esc($settings['representative_name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="default_daily_rate">Giá lưu chuồng mặc định (VNĐ/ngày)</label>
                        <input type="number" id="default_daily_rate" name="default_daily_rate" value="<?= esc($settings['default_daily_rate'] ?? 100000) ?>">
                    </div>
                    <div class="form-group">
                        <label for="checkout_hour">Giờ checkout</label>
                        <input type="time" id="checkout_hour" name="checkout_hour" value="<?= esc($settings['checkout_hour'] ?? '18:00') ?>">
                    </div>
                    <div class="form-group">
                        <label for="overtime_fee_per_hour">Phí quá giờ (VNĐ/giờ)</label>
                        <input type="number" id="overtime_fee_per_hour" name="overtime_fee_per_hour" value="<?= esc($settings['overtime_fee_per_hour'] ?? 10000) ?>">
                    </div>
                    <div class="form-group">
                        <label for="signing_place">Nơi ký</label>
                        <input type="text" id="signing_place" name="signing_place" value="<?= esc($settings['signing_place'] ?? '') ?>">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu cài đặt</button>
                    </div>
                </form>
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>
    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
</body>
</html>
