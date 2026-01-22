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
            <h1>Quản lý lịch hẹn</h1>

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

            <!-- Filter by status -->
            <div class="filter-box" style="margin-bottom: 1.5rem;">
                <label>Lọc theo trạng thái:</label>
                <select id="statusFilter" onchange="filterByStatus()">
                    <option value="">Tất cả</option>
                    <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                    <option value="confirmed" <?= $currentStatus === 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                    <option value="completed" <?= $currentStatus === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                    <option value="cancelled" <?= $currentStatus === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                </select>
            </div>

            <!-- Bảng danh sách -->
            <div class="table-responsive">
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th>Ngày giờ</th>
                            <th>Khách hàng</th>
                            <th>Thú cưng</th>
                            <th>Loại</th>
                            <th>Bác sĩ</th>
                            <th>Dịch vụ</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($appointments)): ?>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($appointment['appointment_date'])) ?></td>
                                    <td><?= esc($appointment['customer_name']) ?></td>
                                    <td><?= esc($appointment['pet_name']) ?></td>
                                    <td><?= esc($appointment['appointment_type']) ?></td>
                                    <td><?= esc($appointment['doctor_name'] ?? 'Chưa chỉ định') ?></td>
                                    <td><?= esc($appointment['service_name'] ?? 'N/A') ?></td>
                                    <td>
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
                                        $status = $appointment['status'];
                                        ?>
                                        <span style="padding: 4px 12px; border-radius: 4px; background: <?= $statusColors[$status] ?? '#999' ?>; color: white; font-size: 0.85rem;">
                                            <?= $statusLabels[$status] ?? $status ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="<?= site_url('admin/appointments/view/' . $appointment['appointment_id']) ?>" class="btn btn-icon btn-edit" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                            
                                            <!-- Quick status update -->
                                            <div class="status-dropdown">
                                                <button class="btn btn-icon btn-success" title="Cập nhật trạng thái">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                                <div class="status-dropdown-menu">
                                                    <form method="POST" action="<?= site_url('admin/appointments/update-status/' . $appointment['appointment_id']) ?>" style="margin: 0;">
                                                        <button type="submit" name="status" value="pending" class="status-dropdown-item <?= $status === 'pending' ? 'active' : '' ?>">Chờ xác nhận</button>
                                                        <button type="submit" name="status" value="confirmed" class="status-dropdown-item <?= $status === 'confirmed' ? 'active' : '' ?>">Đã xác nhận</button>
                                                        <button type="submit" name="status" value="completed" class="status-dropdown-item <?= $status === 'completed' ? 'active' : '' ?>">Hoàn thành</button>
                                                        <button type="submit" name="status" value="cancelled" class="status-dropdown-item <?= $status === 'cancelled' ? 'active' : '' ?>">Đã hủy</button>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                            <a href="<?= site_url('admin/appointments/delete/' . $appointment['appointment_id']) ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa lịch hẹn này?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">Chưa có lịch hẹn nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?><?= $currentStatus ? '&status=' . $currentStatus : '' ?>" class="page-link">&laquo; Trước</a>
                        <?php endif; ?>

                        <?php
                        $maxLinks = 5;
                        $start = max(1, $currentPage - floor($maxLinks / 2));
                        $end = min($totalPages, $start + $maxLinks - 1);

                        if ($end - $start < $maxLinks - 1) {
                            $start = max(1, $end - $maxLinks + 1);
                        }

                        for ($i = $start; $i <= $end; $i++): ?>
                            <a href="?page=<?= $i ?><?= $currentStatus ? '&status=' . $currentStatus : '' ?>" class="page-link <?= $i === $currentPage ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?><?= $currentStatus ? '&status=' . $currentStatus : '' ?>" class="page-link">Sau &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </main>

    <style>
        .status-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .status-dropdown-menu {
            display: none;
            position: fixed;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border-radius: 6px;
            padding: 0.5rem 0;
            z-index: 10000;
            min-width: 160px;
            overflow: visible;
        }
        
        .status-dropdown:hover .status-dropdown-menu {
            display: block !important;
        }
        
        .status-dropdown-item {
            width: 100%;
            text-align: left;
            padding: 0.75rem 1rem;
            border: none;
            background: none;
            cursor: pointer;
            display: block;
            color: #333;
            font-size: 0.9rem;
            transition: background 0.2s;
        }
        
        .status-dropdown-item:hover {
            background: #f0f0f0 !important;
        }
        
        .status-dropdown-item.active {
            background: #f5f5f5;
            font-weight: 600;
        }
        
        /* Tính toán vị trí dropdown */
        .status-dropdown:hover .status-dropdown-menu {
            position: fixed;
        }
    </style>
    
    <script>
        // Tính toán vị trí dropdown khi hover
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.status-dropdown');
            
            dropdowns.forEach(function(dropdown) {
                const button = dropdown.querySelector('button');
                const menu = dropdown.querySelector('.status-dropdown-menu');
                
                dropdown.addEventListener('mouseenter', function() {
                    const rect = button.getBoundingClientRect();
                    const menuHeight = 200; // Ước tính chiều cao menu
                    const windowHeight = window.innerHeight;
                    
                    // Kiểm tra nếu menu sẽ bị cắt ở dưới
                    if (rect.bottom + menuHeight > windowHeight) {
                        // Hiển thị menu phía trên button
                        menu.style.top = (rect.top - menuHeight) + 'px';
                        menu.style.bottom = 'auto';
                    } else {
                        // Hiển thị menu phía dưới button
                        menu.style.top = rect.bottom + 'px';
                        menu.style.bottom = 'auto';
                    }
                    
                    // Căn chỉnh theo chiều ngang
                    menu.style.left = (rect.right - menu.offsetWidth) + 'px';
                    menu.style.right = 'auto';
                    
                    // Đảm bảo không bị tràn ra ngoài màn hình bên trái
                    if (rect.right - menu.offsetWidth < 0) {
                        menu.style.left = rect.left + 'px';
                    }
                });
            });
        });
    </script>

    <script>
        function filterByStatus() {
            const status = document.getElementById('statusFilter').value;
            const url = new URL(window.location.href);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            url.searchParams.delete('page'); // Reset to page 1
            window.location.href = url.toString();
        }
    </script>

    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>

</html>
