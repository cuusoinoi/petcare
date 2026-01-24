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
            <h1>Lịch sử khám bệnh</h1>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Tìm theo khách hàng, thú cưng, bác sĩ...">
                <a href="<?= site_url('admin/medical-records/add') ?>" class="btn btn-add"><i class="fas fa-plus"></i> Tạo phiếu khám</a>
            </div>
            <div class="table-responsive">
                <table class="admin-data-table" id="dataTable">
                    <thead>
                        <tr>
                            <th>Ngày khám</th>
                            <th>Loại</th>
                            <th>Khách hàng</th>
                            <th>Thú cưng</th>
                            <th>Bác sĩ</th>
                            <th>Tóm tắt</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($records)): ?>
                            <?php foreach ($records as $row): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($row['medical_record_visit_date'])) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $row['medical_record_type'] === 'Vaccine' ? 'success' : ($row['medical_record_type'] === 'Khám bệnh' ? 'primary' : 'warning') ?>">
                                            <?= esc($row['medical_record_type']) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($row['customer_name']) ?></td>
                                    <td><?= esc($row['pet_name']) ?></td>
                                    <td><?= esc($row['doctor_name']) ?></td>
                                    <td><?= esc($row['medical_record_summary'] ?? '') ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="<?= site_url('admin/print/medical-record/' . $row['medical_record_id']) ?>" class="btn btn-icon btn-print" title="In phiếu khám" target="_blank"><i class="fas fa-print"></i></a>
                                            <a href="<?= site_url('admin/medical-records/edit/' . $row['medical_record_id']) ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="<?= site_url('admin/medical-records/delete/' . $row['medical_record_id']) ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirmDelete('Bạn có chắc muốn xóa phiếu khám này?', this.href)"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7">Chưa có phiếu khám nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($currentPage > 1): ?><a href="?page=<?= $currentPage - 1 ?>" class="page-link">&laquo; Trước</a><?php endif; ?>
                        <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                            <a href="?page=<?= $i ?>" class="page-link <?= $i === $currentPage ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>
                        <?php if ($currentPage < $totalPages): ?><a href="?page=<?= $currentPage + 1 ?>" class="page-link">Sau &raquo;</a><?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>
    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
    <script>
        document.getElementById("searchInput").addEventListener("keyup", function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll("#dataTable tbody tr").forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(filter) ? "" : "none";
            });
        });
    </script>
</body>
</html>
