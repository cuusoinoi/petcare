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
                <h2>Chỉnh sửa thú cưng</h2>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <form action="<?= site_url('admin/pets/update/' . $pet['pet_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="customer_id">Chủ sở hữu <span class="required-field">*</span></label>
                        <select id="customer_id" name="customer_id" required>
                            <option value="">-- Chọn khách hàng --</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['customer_id'] ?>" <?= $customer['customer_id'] == $pet['customer_id'] ? 'selected' : '' ?>><?= esc($customer['customer_name']) ?> - <?= esc($customer['customer_phone_number']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Tên thú cưng <span class="required-field">*</span></label>
                        <input type="text" id="name" name="name" value="<?= esc($pet['pet_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="species">Loài</label>
                        <input type="text" id="species" name="species" value="<?= esc($pet['pet_species']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="gender">Giới tính</label>
                        <select id="gender" name="gender">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="0" <?= $pet['pet_gender'] === '0' ? 'selected' : '' ?>>Đực</option>
                            <option value="1" <?= $pet['pet_gender'] === '1' ? 'selected' : '' ?>>Cái</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dob">Ngày sinh</label>
                        <input type="date" id="dob" name="dob" value="<?= esc($pet['pet_dob']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="weight">Cân nặng (kg)</label>
                        <input type="number" step="0.01" id="weight" name="weight" value="<?= esc($pet['pet_weight']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="sterilization">Triệt sản</label>
                        <select id="sterilization" name="sterilization">
                            <option value="">-- Chọn --</option>
                            <option value="0" <?= $pet['pet_sterilization'] === '0' ? 'selected' : '' ?>>Chưa triệt sản</option>
                            <option value="1" <?= $pet['pet_sterilization'] === '1' ? 'selected' : '' ?>>Đã triệt sản</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="characteristic">Đặc điểm</label>
                        <textarea id="characteristic" name="characteristic" rows="2"><?= esc($pet['pet_characteristic']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="allergy">Dị ứng thuốc</label>
                        <textarea id="allergy" name="allergy" rows="2"><?= esc($pet['pet_drug_allergy']) ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="<?= site_url('admin/pets') ?>" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>
    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
</body>
</html>
