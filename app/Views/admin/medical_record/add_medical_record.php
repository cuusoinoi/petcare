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
                <h2>Tạo phiếu khám mới</h2>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <form action="<?= site_url('admin/medical-records/store') ?>" method="post">
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
                        <label for="doctor_id">Bác sĩ <span class="required-field">*</span></label>
                        <select id="doctor_id" name="doctor_id" required>
                            <option value="">-- Chọn bác sĩ --</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?= $doctor['doctor_id'] ?>"><?= esc($doctor['doctor_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">Loại khám <span class="required-field">*</span></label>
                        <select id="type" name="type" required>
                            <option value="">-- Chọn loại khám --</option>
                            <option value="Khám bệnh">Khám bệnh</option>
                            <option value="Vaccine">Tiêm Vaccine</option>
                            <option value="Tái khám">Tái khám</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="visit_date">Ngày khám <span class="required-field">*</span></label>
                        <input type="datetime-local" id="visit_date" name="visit_date" value="<?= date('Y-m-d\TH:i') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="summary">Tóm tắt</label>
                        <textarea id="summary" name="summary" rows="2" placeholder="Tóm tắt tình trạng..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="details">Chi tiết</label>
                        <textarea id="details" name="details" rows="4" placeholder="Chi tiết khám bệnh, kết quả, hướng điều trị..."></textarea>
                    </div>
                    <!-- Vaccine fields (hidden by default) -->
                    <div id="vaccineFields" style="display: none;">
                        <h3>Thông tin Vaccine</h3>
                        <div class="form-group">
                            <label for="vaccine_name">Tên vaccine</label>
                            <input type="text" id="vaccine_name" name="vaccine_name" placeholder="Nhập tên vaccine">
                        </div>
                        <div class="form-group">
                            <label for="batch_number">Số lô</label>
                            <input type="text" id="batch_number" name="batch_number" placeholder="Nhập số lô">
                        </div>
                        <div class="form-group">
                            <label for="next_injection_date">Ngày tiêm tiếp theo</label>
                            <input type="date" id="next_injection_date" name="next_injection_date">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="<?= site_url('admin/medical-records') ?>" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>
    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
    <script>
        // Show/hide vaccine fields based on type selection
        document.getElementById('type').addEventListener('change', function() {
            const vaccineFields = document.getElementById('vaccineFields');
            vaccineFields.style.display = this.value === 'Vaccine' ? 'block' : 'none';
        });

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
