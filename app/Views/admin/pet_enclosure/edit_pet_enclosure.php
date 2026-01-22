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
                <h2>Chỉnh sửa lưu chuồng</h2>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <form action="<?= site_url('admin/pet-enclosures/update/' . $enclosure['pet_enclosure_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="customer_id">Khách hàng <span class="required-field">*</span></label>
                        <select id="customer_id" name="customer_id" required>
                            <option value="">-- Chọn khách hàng --</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['customer_id'] ?>" <?= $customer['customer_id'] == $enclosure['customer_id'] ? 'selected' : '' ?>><?= esc($customer['customer_name']) ?> - <?= esc($customer['customer_phone_number']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pet_id">Thú cưng <span class="required-field">*</span></label>
                        <select id="pet_id" name="pet_id" required>
                            <option value="">-- Chọn thú cưng --</option>
                            <?php foreach ($pets as $pet): ?>
                                <option value="<?= $pet['pet_id'] ?>" data-customer="<?= $pet['customer_id'] ?>" <?= $pet['pet_id'] == $enclosure['pet_id'] ? 'selected' : '' ?>><?= esc($pet['pet_name']) ?> - <?= esc($pet['pet_species']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="enclosure_number">Số chuồng <span class="required-field">*</span></label>
                        <input type="text" id="enclosure_number" name="enclosure_number" value="<?= esc($enclosure['pet_enclosure_number']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="check_in_date">Ngày check-in <span class="required-field">*</span></label>
                        <input type="datetime-local" id="check_in_date" name="check_in_date" value="<?= date('Y-m-d\TH:i', strtotime($enclosure['check_in_date'])) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="check_out_date">Ngày check-out</label>
                        <input type="datetime-local" id="check_out_date" name="check_out_date" value="<?= $enclosure['check_out_date'] ? date('Y-m-d\TH:i', strtotime($enclosure['check_out_date'])) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="daily_rate">Giá/ngày (VNĐ) <span class="required-field">*</span></label>
                        <input type="number" id="daily_rate" name="daily_rate" value="<?= esc($enclosure['daily_rate']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="deposit">Đặt cọc (VNĐ)</label>
                        <input type="number" id="deposit" name="deposit" value="<?= esc($enclosure['deposit']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="emergency_limit">Hạn mức cấp cứu (VNĐ)</label>
                        <input type="number" id="emergency_limit" name="emergency_limit" value="<?= esc($enclosure['emergency_limit']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select id="status" name="status">
                            <option value="Check In" <?= $enclosure['pet_enclosure_status'] === 'Check In' ? 'selected' : '' ?>>Check In</option>
                            <option value="Check Out" <?= $enclosure['pet_enclosure_status'] === 'Check Out' ? 'selected' : '' ?>>Check Out</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="note">Ghi chú</label>
                        <textarea id="note" name="note" rows="3"><?= esc($enclosure['pet_enclosure_note']) ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="<?= site_url('admin/pet-enclosures') ?>" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>
    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
</body>
</html>
