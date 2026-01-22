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
            <h1>Danh sách khách hàng</h1>

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

            <!-- Thanh tìm kiếm -->
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Tìm theo tên, SDT, thẻ căn cước, địa chỉ hoặc ghi chú...">
                <a href="<?= site_url('admin/customers/add') ?>" class="btn btn-add"><i class="fas fa-plus"></i> Thêm khách hàng</a>
            </div>

            <!-- Bảng danh sách -->
            <div class="table-responsive">
                <table class="admin-data-table" id="customerTable">
                    <thead>
                        <tr>
                            <th>Họ tên</th>
                            <th>Số điện thoại</th>
                            <th>Thẻ căn cước</th>
                            <th>Địa chỉ</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customers)): ?>
                            <?php foreach ($customers as $row): ?>
                                <tr>
                                    <td><?= esc($row['customer_name']) ?></td>
                                    <td><?= esc($row['customer_phone_number']) ?></td>
                                    <td><?= esc($row['customer_identity_card']) ?></td>
                                    <td><?= esc($row['customer_address']) ?></td>
                                    <td><?= esc($row['customer_note']) ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="<?= site_url('admin/customers/edit/' . $row['customer_id']) ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="<?= site_url('admin/customers/delete/' . $row['customer_id']) ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa khách hàng này?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Chưa có khách hàng nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?>" class="page-link">&laquo; Trước</a>
                        <?php endif; ?>

                        <?php
                        $maxLinks = 5;
                        $start = max(1, $currentPage - floor($maxLinks / 2));
                        $end = min($totalPages, $start + $maxLinks - 1);

                        if ($end - $start < $maxLinks - 1) {
                            $start = max(1, $end - $maxLinks + 1);
                        }

                        for ($i = $start; $i <= $end; $i++): ?>
                            <a href="?page=<?= $i ?>" class="page-link <?= $i === $currentPage ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?>" class="page-link">Sau &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
        
        <?= view('layouts/admin_footer') ?>
    </main>

    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>

    <script>
        // Lọc tự động theo input
        document.getElementById("searchInput").addEventListener("keyup", function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#customerTable tbody tr");

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>
</body>

</html>
