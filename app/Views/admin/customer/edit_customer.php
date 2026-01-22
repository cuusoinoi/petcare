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
                <h2>Chỉnh sửa khách hàng</h2>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error">
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('admin/customers/update/' . $customer['customer_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="fullname">Họ tên <span class="required-field">*</span></label>
                        <input type="text" id="fullname" name="fullname" value="<?= esc($customer['customer_name']) ?>" placeholder="Nhập họ tên khách hàng" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại <span class="required-field">*</span></label>
                        <input type="number" id="phone" name="phone" value="<?= esc($customer['customer_phone_number']) ?>" placeholder="Nhập số điện thoại" required>
                    </div>
                    <div class="form-group">
                        <label for="identity_card">Thẻ căn cước</label>
                        <input type="number" id="identity_card" name="identity_card" value="<?= esc($customer['customer_identity_card']) ?>" placeholder="Nhập thẻ căn cước">
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ <span class="required-field">*</span></label>
                        <input type="text" id="address" name="address" value="<?= esc($customer['customer_address']) ?>" placeholder="Nhập địa chỉ khách hàng" required>
                    </div>
                    <div class="form-group">
                        <label for="note">Ghi chú</label>
                        <textarea id="note" name="note" rows="3" placeholder="Nhập ghi chú (nếu có)..."><?= esc($customer['customer_note']) ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="<?= site_url('admin/customers') ?>" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        
        <?= view('layouts/admin_footer') ?>
    </main>

    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
</body>

</html>
