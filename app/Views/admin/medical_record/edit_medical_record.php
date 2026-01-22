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
                <h2>Chỉnh sửa phiếu khám</h2>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <form action="<?= site_url('admin/medical-records/update/' . $record['medical_record_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="customer_id">Khách hàng <span class="required-field">*</span></label>
                        <select id="customer_id" name="customer_id" required>
                            <option value="">-- Chọn khách hàng --</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['customer_id'] ?>" <?= $customer['customer_id'] == $record['customer_id'] ? 'selected' : '' ?>><?= esc($customer['customer_name']) ?> - <?= esc($customer['customer_phone_number']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pet_id">Thú cưng <span class="required-field">*</span></label>
                        <select id="pet_id" name="pet_id" required>
                            <option value="">-- Chọn thú cưng --</option>
                            <?php foreach ($pets as $pet): ?>
                                <option value="<?= $pet['pet_id'] ?>" data-customer="<?= $pet['customer_id'] ?>" <?= $pet['pet_id'] == $record['pet_id'] ? 'selected' : '' ?>><?= esc($pet['pet_name']) ?> - <?= esc($pet['pet_species']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="doctor_id">Bác sĩ <span class="required-field">*</span></label>
                        <select id="doctor_id" name="doctor_id" required>
                            <option value="">-- Chọn bác sĩ --</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?= $doctor['doctor_id'] ?>" <?= $doctor['doctor_id'] == $record['doctor_id'] ? 'selected' : '' ?>><?= esc($doctor['doctor_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">Loại khám <span class="required-field">*</span></label>
                        <select id="type" name="type" required>
                            <option value="">-- Chọn loại khám --</option>
                            <option value="Khám bệnh" <?= $record['medical_record_type'] === 'Khám bệnh' ? 'selected' : '' ?>>Khám bệnh</option>
                            <option value="Vaccine" <?= $record['medical_record_type'] === 'Vaccine' ? 'selected' : '' ?>>Tiêm Vaccine</option>
                            <option value="Tái khám" <?= $record['medical_record_type'] === 'Tái khám' ? 'selected' : '' ?>>Tái khám</option>
                            <option value="Khác" <?= $record['medical_record_type'] === 'Khác' ? 'selected' : '' ?>>Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="visit_date">Ngày khám <span class="required-field">*</span></label>
                        <input type="datetime-local" id="visit_date" name="visit_date" value="<?= date('Y-m-d\TH:i', strtotime($record['medical_record_visit_date'])) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="summary">Tóm tắt</label>
                        <textarea id="summary" name="summary" rows="2"><?= esc($record['medical_record_summary']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="details">Chi tiết</label>
                        <textarea id="details" name="details" rows="4"><?= esc($record['medical_record_details']) ?></textarea>
                    </div>
                    <!-- Vaccine fields -->
                    <div id="vaccineFields" style="display: <?= $record['medical_record_type'] === 'Vaccine' ? 'block' : 'none' ?>;">
                        <h3>Thông tin Vaccine</h3>
                        <div class="form-group">
                            <label for="vaccine_name">Tên vaccine</label>
                            <input type="text" id="vaccine_name" name="vaccine_name" value="<?= esc($vaccination['vaccine_name'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="batch_number">Số lô</label>
                            <input type="text" id="batch_number" name="batch_number" value="<?= esc($vaccination['batch_number'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="next_injection_date">Ngày tiêm tiếp theo</label>
                            <input type="date" id="next_injection_date" name="next_injection_date" value="<?= esc($vaccination['next_injection_date'] ?? '') ?>">
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
    </script>
</body>
</html>
