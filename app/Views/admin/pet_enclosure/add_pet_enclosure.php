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
                <h2>Thêm lưu chuồng mới</h2>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <form action="<?= site_url('admin/pet-enclosures/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="customer_id">Khách hàng <span class="required-field">*</span></label>
                        <select id="customer_id" name="customer_id" required>
                            <option value="">-- Chọn khách hàng --</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['customer_id'] ?>"><?= esc($customer['customer_name']) ?> - <?= esc($customer['customer_phone_number']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pet_id">Thú cưng <span class="required-field">*</span></label>
                        <select id="pet_id" name="pet_id" required>
                            <option value="">-- Chọn thú cưng --</option>
                            <?php foreach ($pets as $pet): ?>
                                <option value="<?= $pet['pet_id'] ?>" data-customer="<?= $pet['customer_id'] ?>"><?= esc($pet['pet_name']) ?> - <?= esc($pet['pet_species']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="enclosure_number">Số chuồng <span class="required-field">*</span></label>
                        <input type="text" id="enclosure_number" name="enclosure_number" placeholder="VD: A01, B02..." required>
                    </div>
                    <div class="form-group">
                        <label for="check_in_date">Ngày check-in <span class="required-field">*</span></label>
                        <input type="datetime-local" id="check_in_date" name="check_in_date" value="<?= date('Y-m-d\TH:i') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="daily_rate">Giá/ngày (VNĐ) <span class="required-field">*</span></label>
                        <input type="number" id="daily_rate" name="daily_rate" value="<?= $settings['daily_rate'] ?? 100000 ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="deposit">Đặt cọc (VNĐ)</label>
                        <input type="number" id="deposit" name="deposit" value="0">
                    </div>
                    <div class="form-group">
                        <label for="emergency_limit">Hạn mức cấp cứu (VNĐ)</label>
                        <input type="number" id="emergency_limit" name="emergency_limit" value="0">
                    </div>
                    <div class="form-group">
                        <label for="note">Ghi chú</label>
                        <textarea id="note" name="note" rows="3" placeholder="Nhập ghi chú..."></textarea>
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
    <script>
        // Filter pets by customer
        document.getElementById('customer_id').addEventListener('change', function() {
            const customerId = this.value;
            const petSelect = document.getElementById('pet_id');
            const options = petSelect.querySelectorAll('option');
            
            options.forEach(option => {
                if (option.value === '' || option.dataset.customer === customerId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
            petSelect.value = '';
        });
    </script>
</body>
</html>
