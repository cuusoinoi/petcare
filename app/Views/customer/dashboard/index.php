<?= view('layouts/customer_header', ['settings' => $settings ?? []]) ?>

<section class="section">
    <div class="container">
        <h2 class="section-title">Dashboard</h2>
        <p style="text-align: center; margin-bottom: 3rem; font-size: 1.6rem; color: #666;">
            Xin chào, <?= esc($customer['customer_name'] ?? 'Khách hàng') ?>!
        </p>

        <!-- Statistics -->
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="dashboard-card-icon">
                    <i class="fas fa-paw"></i>
                </div>
                <div class="dashboard-card-value"><?= $totalPets ?></div>
                <div class="dashboard-card-label">Thú cưng</div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="dashboard-card-value"><?= $totalAppointments ?></div>
                <div class="dashboard-card-label">Lịch hẹn</div>
            </div>

            <a href="<?= site_url('customer/dashboard/invoices') ?>" style="text-decoration: none; color: inherit;">
                <div class="dashboard-card" style="cursor: pointer; transition: transform 0.2s;">
                    <div class="dashboard-card-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="dashboard-card-value"><?= $totalInvoices ?></div>
                    <div class="dashboard-card-label">Hóa đơn</div>
                </div>
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="service-card" style="margin-top: 3rem;">
            <h3 style="margin-bottom: 2rem; color: var(--primary-color);">Thao tác nhanh</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <a href="<?= site_url('customer/booking') ?>" class="btn-submit" style="text-decoration: none; text-align: center; display: block;">
                    <i class="fas fa-calendar-plus"></i> Đặt lịch
                </a>
                <a href="<?= site_url('customer/dashboard/pets') ?>" class="btn-submit" style="text-decoration: none; text-align: center; display: block; background: var(--secondary-color);">
                    <i class="fas fa-paw"></i> Thú cưng
                </a>
                <a href="<?= site_url('customer/dashboard/medical-records') ?>" class="btn-submit" style="text-decoration: none; text-align: center; display: block; background: #007bff;">
                    <i class="fas fa-file-medical"></i> Hồ sơ khám
                </a>
                <a href="<?= site_url('customer/dashboard/invoices') ?>" class="btn-submit" style="text-decoration: none; text-align: center; display: block; background: #6c757d;">
                    <i class="fas fa-receipt"></i> Hóa đơn
                </a>
                <a href="<?= site_url('customer/booking/my-appointments') ?>" class="btn-submit" style="text-decoration: none; text-align: center; display: block; background: #28a745;">
                    <i class="fas fa-list"></i> Lịch hẹn
                </a>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <?php if (!empty($upcomingAppointments)): ?>
            <div class="service-card" style="margin-top: 3rem;">
                <h3 style="margin-bottom: 2rem; color: var(--primary-color);">Lịch hẹn sắp tới</h3>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f4f6f9;">
                                <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #ddd;">Ngày giờ</th>
                                <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #ddd;">Thú cưng</th>
                                <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #ddd;">Loại</th>
                                <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #ddd;">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($upcomingAppointments as $apt): ?>
                                <tr>
                                    <td style="padding: 1rem; border-bottom: 1px solid #ddd;">
                                        <?= date('d/m/Y H:i', strtotime($apt['appointment_date'])) ?>
                                    </td>
                                    <td style="padding: 1rem; border-bottom: 1px solid #ddd;">
                                        <?= esc($apt['pet_name'] ?? 'N/A') ?>
                                    </td>
                                    <td style="padding: 1rem; border-bottom: 1px solid #ddd;">
                                        <?= esc($apt['appointment_type']) ?>
                                    </td>
                                    <td style="padding: 1rem; border-bottom: 1px solid #ddd;">
                                        <span style="padding: 0.3rem 0.8rem; border-radius: 4px; font-size: 1.2rem;
                                            background: <?= $apt['status'] === 'confirmed' ? '#d4edda' : '#fff3cd' ?>;
                                            color: <?= $apt['status'] === 'confirmed' ? '#155724' : '#856404' ?>;">
                                            <?= $apt['status'] === 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= view('layouts/customer_footer', ['settings' => $settings ?? []]) ?>
