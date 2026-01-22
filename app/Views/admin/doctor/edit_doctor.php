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
                <h2>Chỉnh sửa bác sĩ</h2>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <form action="<?= site_url('admin/doctors/update/' . $doctor['doctor_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="fullname">Họ tên <span class="required-field">*</span></label>
                        <input type="text" id="fullname" name="fullname" value="<?= esc($doctor['doctor_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại <span class="required-field">*</span></label>
                        <input type="text" id="phone" name="phone" value="<?= esc($doctor['doctor_phone_number']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="identity_card">CCCD</label>
                        <input type="text" id="identity_card" name="identity_card" value="<?= esc($doctor['doctor_identity_card']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ <span class="required-field">*</span></label>
                        <input type="text" id="address" name="address" value="<?= esc($doctor['doctor_address']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="note">Ghi chú</label>
                        <textarea id="note" name="note" rows="3"><?= esc($doctor['doctor_note']) ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="<?= site_url('admin/doctors') ?>" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>
    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
</body>
</html>
