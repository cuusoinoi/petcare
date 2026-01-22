<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('admin_assets/images/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <div class="overlay" id="overlay"></div>
    <?= view('layouts/admin_sidebar') ?>
    <main class="main">
        <?= view('layouts/admin_header') ?>
        <main class="content">
            <div class="form-container">
                <h2>Chỉnh sửa tiêm chủng</h2>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <form action="<?= site_url('admin/pet-vaccinations/update/' . $vaccination['pet_vaccination_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="vaccine_id">Vaccine <span class="required-field">*</span></label>
                        <select id="vaccine_id" name="vaccine_id" required>
                            <option value="">-- Chọn vaccine --</option>
                            <?php foreach ($vaccines as $vaccine): ?>
                                <option value="<?= $vaccine['vaccine_id'] ?>" <?= $vaccine['vaccine_id'] == $vaccination['vaccine_id'] ? 'selected' : '' ?>><?= esc($vaccine['vaccine_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="customer_id">Khách hàng <span class="required-field">*</span></label>
                        <select id="customer_id" name="customer_id" required>
                            <option value="">-- Chọn khách hàng --</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['customer_id'] ?>" <?= $customer['customer_id'] == $vaccination['customer_id'] ? 'selected' : '' ?>><?= esc($customer['customer_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pet_id">Thú cưng <span class="required-field">*</span></label>
                        <select id="pet_id" name="pet_id" required>
                            <option value="">-- Chọn thú cưng --</option>
                            <?php foreach ($pets as $pet): ?>
                                <option value="<?= $pet['pet_id'] ?>" data-customer="<?= $pet['customer_id'] ?>" <?= $pet['pet_id'] == $vaccination['pet_id'] ? 'selected' : '' ?>><?= esc($pet['pet_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="doctor_id">Bác sĩ <span class="required-field">*</span></label>
                        <select id="doctor_id" name="doctor_id" required>
                            <option value="">-- Chọn bác sĩ --</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?= $doctor['doctor_id'] ?>" <?= $doctor['doctor_id'] == $vaccination['doctor_id'] ? 'selected' : '' ?>><?= esc($doctor['doctor_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="vaccination_date">Ngày tiêm <span class="required-field">*</span></label>
                        <input type="date" id="vaccination_date" name="vaccination_date" value="<?= $vaccination['vaccination_date'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="next_vaccination_date">Ngày tiêm tiếp theo</label>
                        <input type="date" id="next_vaccination_date" name="next_vaccination_date" value="<?= $vaccination['next_vaccination_date'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="notes">Ghi chú</label>
                        <textarea id="notes" name="notes" rows="3"><?= esc($vaccination['notes']) ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="<?= site_url('admin/pet-vaccinations') ?>" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>
    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
</body>
</html>
