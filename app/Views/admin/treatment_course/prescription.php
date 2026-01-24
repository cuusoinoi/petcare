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
            <div class="form-container form-container-wide">
                <div class="back-box">
                    <a href="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions') ?>" class="btn btn-back"><i class="fas fa-arrow-left"></i> Quay lại</a>
                </div>
                <h2>Đơn thuốc - Buổi điều trị <?= date('d/m/Y H:i', strtotime($session['treatment_session_datetime'])) ?></h2>
                <p><strong>Khách hàng:</strong> <?= esc($customer['customer_name']) ?> | <strong>Thú cưng:</strong> <?= esc($pet['pet_name']) ?> | <strong>Bác sĩ:</strong> <?= esc($doctor['doctor_name']) ?></p>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <!-- Danh sách thuốc đã kê -->
                <h3>Danh sách thuốc</h3>
                <div class="table-responsive">
                    <table class="admin-data-table">
                        <thead>
                            <tr>
                                <th>Thuốc</th>
                                <th>Loại điều trị</th>
                                <th>Liều lượng</th>
                                <th>Đơn vị</th>
                                <th>Tần suất</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($prescriptions)): ?>
                                <?php foreach ($prescriptions as $p): ?>
                                    <tr>
                                        <td><?= esc($p['medicine_name']) ?> (<?= esc($p['medicine_route']) ?>)</td>
                                        <td><?= esc($p['treatment_type']) ?></td>
                                        <td><?= esc($p['dosage']) ?></td>
                                        <td><?= esc($p['unit']) ?></td>
                                        <td><?= esc($p['frequency']) ?></td>
                                        <td>
                                            <span class="badge badge-<?= $p['status'] == 1 ? 'success' : 'secondary' ?>">
                                                <?= $p['status'] == 1 ? 'Đang dùng' : 'Hoàn thành' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions/' . $session['treatment_session_id'] . '/prescription/delete/' . $p['prescription_id']) ?>" class="btn btn-icon btn-delete" onclick="return confirmDelete('Bạn có chắc muốn xóa thuốc này?', this.href)"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7">Chưa có thuốc nào trong đơn.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Form thêm thuốc mới -->
                <h3>Thêm thuốc vào đơn</h3>
                <form action="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions/' . $session['treatment_session_id'] . '/prescription/add') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="medicine_id">Thuốc <span class="required-field">*</span></label>
                            <select id="medicine_id" name="medicine_id" required>
                                <option value="">-- Chọn thuốc --</option>
                                <?php foreach ($medicines as $medicine): ?>
                                    <option value="<?= $medicine['medicine_id'] ?>"><?= esc($medicine['medicine_name']) ?> (<?= esc($medicine['medicine_route']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="treatment_type">Loại điều trị</label>
                            <input type="text" id="treatment_type" name="treatment_type" placeholder="VD: Điều trị chính, Hỗ trợ...">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="dosage">Liều lượng</label>
                            <input type="text" id="dosage" name="dosage" placeholder="VD: 1, 2, 0.5...">
                        </div>
                        <div class="form-group">
                            <label for="unit">Đơn vị</label>
                            <input type="text" id="unit" name="unit" placeholder="VD: viên, ml, mg...">
                        </div>
                        <div class="form-group">
                            <label for="frequency">Tần suất</label>
                            <input type="text" id="frequency" name="frequency" placeholder="VD: 2 lần/ngày">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <select id="status" name="status">
                                <option value="1">Đang dùng</option>
                                <option value="0">Hoàn thành</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes">Ghi chú</label>
                            <input type="text" id="notes" name="notes" placeholder="Ghi chú thêm...">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-add"><i class="fas fa-plus"></i> Thêm thuốc</button>
                        <a href="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions/' . $session['treatment_session_id'] . '/diagnosis') ?>" class="btn btn-diagnose"><i class="fas fa-notes-medical"></i> Xem chẩn đoán</a>
                    </div>
                </form>
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>
    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
</body>
</html>
