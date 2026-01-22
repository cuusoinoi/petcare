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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h1>Chi tiết lịch hẹn</h1>
                <a href="<?= site_url('admin/appointments') ?>" class="btn btn-back"><i class="fas fa-arrow-left"></i> Quay lại</a>
            </div>

            <!-- Flash messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if ($appointment): ?>
                <div class="card" style="margin-bottom: 2rem;">
                    <h2 style="margin-bottom: 1.5rem; color: var(--primary-color);">Thông tin lịch hẹn</h2>
                    
                    <form method="POST" action="<?= site_url('admin/appointments/update/' . $appointment['appointment_id']) ?>">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Mã lịch hẹn</label>
                                <input type="text" value="#<?= $appointment['appointment_id'] ?>" disabled>
                            </div>
                            
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <?php
                                $statusLabels = [
                                    'pending' => 'Chờ xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'completed' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy'
                                ];
                                $statusColors = [
                                    'pending' => '#ff9800',
                                    'confirmed' => '#2196f3',
                                    'completed' => '#4caf50',
                                    'cancelled' => '#f44336'
                                ];
                                $currentStatus = $appointment['status'];
                                ?>
                                <select name="status" required>
                                    <?php foreach ($statusLabels as $key => $label): ?>
                                        <option value="<?= $key ?>" <?= $currentStatus === $key ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Khách hàng</label>
                                <input type="text" value="<?= esc($appointment['customer_name']) ?> (<?= esc($appointment['customer_phone_number']) ?>)" disabled>
                            </div>
                            
                            <div class="form-group">
                                <label>Thú cưng</label>
                                <input type="text" value="<?= esc($appointment['pet_name']) ?> (<?= esc($appointment['pet_species'] ?? 'N/A') ?>)" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Loại dịch vụ <span style="color: red;">*</span></label>
                                <select name="appointment_type" required>
                                    <option value="Khám" <?= $appointment['appointment_type'] === 'Khám' ? 'selected' : '' ?>>Khám</option>
                                    <option value="Spa" <?= $appointment['appointment_type'] === 'Spa' ? 'selected' : '' ?>>Spa</option>
                                    <option value="Tiêm chủng" <?= $appointment['appointment_type'] === 'Tiêm chủng' ? 'selected' : '' ?>>Tiêm chủng</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Ngày giờ hẹn <span style="color: red;">*</span></label>
                                <input type="datetime-local" name="appointment_date" 
                                       value="<?= date('Y-m-d\TH:i', strtotime($appointment['appointment_date'])) ?>" 
                                       required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Bác sĩ</label>
                                <select name="doctor_id">
                                    <option value="">-- Chưa chỉ định --</option>
                                    <?php if (!empty($doctors)): ?>
                                        <?php foreach ($doctors as $doctor): ?>
                                            <option value="<?= $doctor['doctor_id'] ?>" 
                                                    <?= $appointment['doctor_id'] == $doctor['doctor_id'] ? 'selected' : '' ?>>
                                                <?= esc($doctor['doctor_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Dịch vụ</label>
                                <select name="service_type_id">
                                    <option value="">-- Chưa chọn --</option>
                                    <?php if (!empty($services)): ?>
                                        <?php foreach ($services as $service): ?>
                                            <option value="<?= $service['service_type_id'] ?>" 
                                                    <?= $appointment['service_type_id'] == $service['service_type_id'] ? 'selected' : '' ?>>
                                                <?= esc($service['service_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Ghi chú</label>
                            <textarea name="notes" rows="4" placeholder="Ghi chú về lịch hẹn..."><?= esc($appointment['notes'] ?? '') ?></textarea>
                        </div>

                        <div class="form-actions" style="margin-top: 1.5rem;">
                            <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Cập nhật</button>
                            <a href="<?= site_url('admin/appointments') ?>" class="btn btn-cancel">Hủy</a>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <h3 style="margin-bottom: 1rem; color: var(--primary-color);">Thông tin bổ sung</h3>
                    <table class="admin-data-table" style="margin: 0;">
                        <tr>
                            <td style="width: 200px; font-weight: 600;">Ngày tạo:</td>
                            <td><?= $appointment['created_at'] ? date('d/m/Y H:i:s', strtotime($appointment['created_at'])) : 'N/A' ?></td>
                        </tr>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-error">
                    Không tìm thấy lịch hẹn.
                </div>
            <?php endif; ?>
        </main>
    </main>

    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>

</html>
