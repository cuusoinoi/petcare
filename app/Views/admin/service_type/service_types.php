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
            <h1>Danh sách loại dịch vụ</h1>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Tìm theo tên dịch vụ...">
                <a href="<?= site_url('admin/service-types/add') ?>" class="btn btn-add"><i class="fas fa-plus"></i> Thêm loại dịch vụ</a>
            </div>
            <div class="table-responsive">
                <table class="admin-data-table" id="dataTable">
                    <thead>
                        <tr>
                            <th>Tên dịch vụ</th>
                            <th>Mô tả</th>
                            <th>Giá (VNĐ)</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($serviceTypes)): ?>
                            <?php foreach ($serviceTypes as $row): ?>
                                <tr>
                                    <td><?= esc($row['service_name']) ?></td>
                                    <td><?= esc($row['description'] ?? '') ?></td>
                                    <td><?= number_format($row['price'] ?? 0, 0, ',', '.') ?> đ</td>
                                    <td>
                                        <div class="actions">
                                            <a href="<?= site_url('admin/service-types/edit/' . $row['service_type_id']) ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="<?= site_url('admin/service-types/delete/' . $row['service_type_id']) ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4">Chưa có loại dịch vụ nào.</td></tr>
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
