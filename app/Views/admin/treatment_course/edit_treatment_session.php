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
                <h2>Chỉnh sửa buổi điều trị</h2>
                <p><strong>Khách hàng:</strong> <?= esc($customer['customer_name']) ?> | <strong>Thú cưng:</strong> <?= esc($pet['pet_name']) ?></p>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                
                <form action="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions/update/' . $session['treatment_session_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="doctor_id">Bác sĩ <span class="required-field">*</span></label>
                        <select id="doctor_id" name="doctor_id" required>
                            <option value="">-- Chọn bác sĩ --</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?= $doctor['doctor_id'] ?>" <?= $doctor['doctor_id'] == $session['doctor_id'] ? 'selected' : '' ?>><?= esc($doctor['doctor_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="datetime">Ngày giờ <span class="required-field">*</span></label>
                        <input type="datetime-local" id="datetime" name="datetime" value="<?= date('Y-m-d\TH:i', strtotime($session['treatment_session_datetime'])) ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="temperature">Nhiệt độ (°C)</label>
                            <input type="number" step="0.1" id="temperature" name="temperature" value="<?= $session['temperature'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="weight">Cân nặng (kg)</label>
                            <input type="number" step="0.01" id="weight" name="weight" value="<?= $session['weight'] ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="pulse_rate">Mạch (lần/phút)</label>
                            <input type="number" id="pulse_rate" name="pulse_rate" value="<?= $session['pulse_rate'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="respiratory_rate">Nhịp thở (lần/phút)</label>
                            <input type="number" id="respiratory_rate" name="respiratory_rate" value="<?= $session['respiratory_rate'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="overall_notes">Ghi chú tổng quát</label>
                        <textarea id="overall_notes" name="overall_notes" rows="4"><?= esc($session['overall_notes']) ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions') ?>" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>
    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
</body>
</html>
