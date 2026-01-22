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
            <div class="back-box">
                <a href="<?= site_url('admin/treatment-courses') ?>" class="btn btn-back"><i class="fas fa-arrow-left"></i> Quay lại</a>
            </div>
            <h1>Buổi điều trị - Liệu trình #<?= $course['treatment_course_id'] ?></h1>
            <p><strong>Khách hàng:</strong> <?= esc($customer['customer_name']) ?> | <strong>Thú cưng:</strong> <?= esc($pet['pet_name']) ?></p>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Tìm kiếm...">
                <a href="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions/add') ?>" class="btn btn-add"><i class="fas fa-plus"></i> Thêm buổi điều trị</a>
            </div>
            
            <div class="table-responsive">
                <table class="admin-data-table" id="dataTable">
                    <thead>
                        <tr>
                            <th>Ngày giờ</th>
                            <th>Bác sĩ</th>
                            <th>Nhiệt độ</th>
                            <th>Cân nặng</th>
                            <th>Mạch</th>
                            <th>Nhịp thở</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sessions)): ?>
                            <?php foreach ($sessions as $row): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($row['treatment_session_datetime'])) ?></td>
                                    <td><?= esc($row['doctor_name']) ?></td>
                                    <td><?= $row['temperature'] ? $row['temperature'] . '°C' : '-' ?></td>
                                    <td><?= $row['weight'] ? $row['weight'] . ' kg' : '-' ?></td>
                                    <td><?= $row['pulse_rate'] ?? '-' ?></td>
                                    <td><?= $row['respiratory_rate'] ?? '-' ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="<?= site_url('admin/print/treatment-session/' . $course['treatment_course_id'] . '/' . $row['treatment_session_id']) ?>" class="btn btn-icon btn-print" title="In phiếu" target="_blank"><i class="fas fa-print"></i></a>
                                            <a href="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions/' . $row['treatment_session_id'] . '/diagnosis') ?>" class="btn btn-icon btn-diagnose" title="Chẩn đoán"><i class="fas fa-notes-medical"></i></a>
                                            <a href="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions/' . $row['treatment_session_id'] . '/prescription') ?>" class="btn btn-icon btn-prescription" title="Đơn thuốc"><i class="fas fa-prescription"></i></a>
                                            <a href="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions/edit/' . $row['treatment_session_id']) ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="<?= site_url('admin/treatment-courses/' . $course['treatment_course_id'] . '/sessions/delete/' . $row['treatment_session_id']) ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Xóa buổi điều trị này?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7">Chưa có buổi điều trị nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>
    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
</body>
</html>
