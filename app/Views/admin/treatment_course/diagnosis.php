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
                <div class="back-box">
                    <a href="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions') ?>" class="btn btn-back"><i class="fas fa-arrow-left"></i> Quay lại</a>
                </div>
                <h2>Chẩn đoán - Buổi điều trị <?= date('d/m/Y H:i', strtotime($session['treatment_session_datetime'])) ?></h2>
                <p><strong>Khách hàng:</strong> <?= esc($customer['customer_name']) ?> | <strong>Thú cưng:</strong> <?= esc($pet['pet_name']) ?> | <strong>Bác sĩ:</strong> <?= esc($doctor['doctor_name']) ?></p>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                
                <form action="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions/' . $session['treatment_session_id'] . '/diagnosis/save') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="diagnosis_name">Tên chẩn đoán</label>
                        <input type="text" id="diagnosis_name" name="diagnosis_name" value="<?= esc($diagnosis['diagnosis_name'] ?? '') ?>" placeholder="Nhập tên chẩn đoán">
                    </div>
                    <div class="form-group">
                        <label for="diagnosis_type">Loại chẩn đoán</label>
                        <select id="diagnosis_type" name="diagnosis_type">
                            <option value="">-- Chọn loại --</option>
                            <option value="Sơ bộ" <?= ($diagnosis['diagnosis_type'] ?? '') === 'Sơ bộ' ? 'selected' : '' ?>>Sơ bộ</option>
                            <option value="Xác định" <?= ($diagnosis['diagnosis_type'] ?? '') === 'Xác định' ? 'selected' : '' ?>>Xác định</option>
                            <option value="Phân biệt" <?= ($diagnosis['diagnosis_type'] ?? '') === 'Phân biệt' ? 'selected' : '' ?>>Phân biệt</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="clinical_tests">Xét nghiệm lâm sàng</label>
                        <textarea id="clinical_tests" name="clinical_tests" rows="4" placeholder="Mô tả các xét nghiệm lâm sàng..."><?= esc($diagnosis['clinical_tests'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="notes">Ghi chú</label>
                        <textarea id="notes" name="notes" rows="4" placeholder="Nhập ghi chú..."><?= esc($diagnosis['notes'] ?? '') ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu chẩn đoán</button>
                        <a href="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions/' . $session['treatment_session_id'] . '/prescription') ?>" class="btn btn-prescription"><i class="fas fa-prescription"></i> Xem đơn thuốc</a>
                    </div>
                </form>
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>
    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
</body>
</html>
