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
                <h2>Thêm tiêm chủng mới</h2>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <form action="<?= site_url('admin/pet-vaccinations/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="vaccine_id">Vaccine <span class="required-field">*</span></label>
                        <select id="vaccine_id" name="vaccine_id" required>
                            <option value="">-- Chọn vaccine --</option>
                            <?php foreach ($vaccines as $vaccine): ?>
                                <option value="<?= $vaccine['vaccine_id'] ?>"><?= esc($vaccine['vaccine_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
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
                                <option value="<?= $pet['pet_id'] ?>" data-customer="<?= $pet['customer_id'] ?>"><?= esc($pet['pet_name']) ?></option>
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
                        <label for="vaccination_date">Ngày tiêm <span class="required-field">*</span></label>
                        <input type="date" id="vaccination_date" name="vaccination_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="next_vaccination_date">Ngày tiêm tiếp theo</label>
                        <input type="date" id="next_vaccination_date" name="next_vaccination_date">
                    </div>
                    <div class="form-group">
                        <label for="notes">Ghi chú</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Nhập ghi chú..."></textarea>
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
    <script>
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
