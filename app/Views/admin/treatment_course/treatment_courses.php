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
            <h1>Danh sách liệu trình điều trị</h1>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Tìm theo khách hàng, thú cưng...">
                <a href="<?= site_url('admin/treatment-courses/add') ?>" class="btn btn-add"><i class="fas fa-plus"></i> Thêm liệu trình</a>
            </div>
            <div class="table-responsive">
                <table class="admin-data-table" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Thú cưng</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($courses)): ?>
                            <?php foreach ($courses as $row): ?>
                                <tr>
                                    <td><?= $row['treatment_course_id'] ?></td>
                                    <td><?= esc($row['customer_name']) ?></td>
                                    <td><?= esc($row['pet_name']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['start_date'])) ?></td>
                                    <td><?= $row['end_date'] ? date('d/m/Y', strtotime($row['end_date'])) : '-' ?></td>
                                    <td>
                                        <span class="badge badge-<?= $row['status'] == 1 ? 'success' : 'secondary' ?>">
                                            <?= $row['status'] == 1 ? 'Đang điều trị' : 'Đã kết thúc' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="<?= site_url('admin/treatment-courses/' . $row['treatment_course_id'] . '/sessions') ?>" class="btn btn-icon btn-steth" title="Buổi điều trị"><i class="fas fa-stethoscope"></i></a>
                                            <?php if ($row['status'] == 1): ?>
                                                <a href="<?= site_url('admin/treatment-courses/complete/' . $row['treatment_course_id']) ?>" class="btn btn-icon btn-success" title="Kết thúc" onclick="return confirm('Kết thúc liệu trình này?')"><i class="fas fa-check"></i></a>
                                            <?php endif; ?>
                                            <a href="<?= site_url('admin/treatment-courses/edit/' . $row['treatment_course_id']) ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="<?= site_url('admin/treatment-courses/delete/' . $row['treatment_course_id']) ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7">Chưa có liệu trình nào.</td></tr>
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
