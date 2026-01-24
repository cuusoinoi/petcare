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
            <h1>Quản lý người dùng</h1>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Tìm theo tên đăng nhập, họ tên...">
                <a href="<?= site_url('admin/users/add') ?>" class="btn btn-add"><i class="fas fa-plus"></i> Thêm người dùng</a>
            </div>
            <div class="table-responsive">
                <table class="admin-data-table" id="dataTable">
                    <thead>
                        <tr>
                            <th>Tên đăng nhập</th>
                            <th>Họ tên</th>
                            <th>Vai trò</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $row): ?>
                                <?php
                                $roleLabels = [
                                    'admin' => 'Quản trị viên',
                                    'staff' => 'Nhân viên',
                                    'customer' => 'Khách hàng',
                                    'doctor' => 'Bác sĩ'
                                ];
                                $roleColors = [
                                    'admin' => '#dc3545',
                                    'staff' => '#007bff',
                                    'customer' => '#6c757d',
                                    'doctor' => '#28a745'
                                ];
                                $role = $row['role'] ?? 'staff';
                                $roleLabel = $roleLabels[$role] ?? ucfirst($role);
                                $roleColor = $roleColors[$role] ?? '#6c757d';
                                ?>
                                <tr>
                                    <td><?= esc($row['username']) ?></td>
                                    <td><?= esc($row['fullname']) ?></td>
                                    <td>
                                        <span style="padding: 0.3rem 0.8rem; border-radius: 4px; font-size: 1.2rem; font-weight: 500;
                                            background: <?= $roleColor ?>20;
                                            color: <?= $roleColor ?>;">
                                            <?= esc($roleLabel) ?>
                                        </span>
                                    </td>
                                    <td><?= !empty($row['create_at']) ? date('d/m/Y H:i', strtotime($row['create_at'])) : 'N/A' ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="<?= site_url('admin/users/edit/' . $row['id']) ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="<?= site_url('admin/users/delete/' . $row['id']) ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirmDelete('Bạn có chắc muốn xóa người dùng này?', this.href)"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5">Chưa có người dùng nào.</td></tr>
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
    <script>
        // Đảm bảo confirmDelete được định nghĩa ngay lập tức
        window.confirmDelete = function(message, url) {
            // Nếu showConfirm đã được định nghĩa, sử dụng nó
            if (typeof showConfirm === 'function') {
                showConfirm(message || 'Bạn có chắc muốn xóa?', 'Xác nhận').then(result => {
                    if (result && url) {
                        window.location.href = url;
                    }
                });
            } else {
                // Fallback nếu script chưa load - chờ script load xong
                const checkShowConfirm = setInterval(() => {
                    if (typeof showConfirm === 'function') {
                        clearInterval(checkShowConfirm);
                        showConfirm(message || 'Bạn có chắc muốn xóa?', 'Xác nhận').then(result => {
                            if (result && url) {
                                window.location.href = url;
                            }
                        });
                    }
                }, 50);
                
                // Timeout sau 1 giây, sử dụng confirm mặc định
                setTimeout(() => {
                    clearInterval(checkShowConfirm);
                    if (confirm(message || 'Bạn có chắc muốn xóa?')) {
                        window.location.href = url;
                    }
                }, 1000);
            }
            return false;
        };
    </script>
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
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
