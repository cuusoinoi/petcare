<?= view('layouts/customer_header', ['settings' => $settings ?? []]) ?>

<section class="section">
    <div class="container">
        <h2 class="section-title">Lịch hẹn của tôi</h2>

        <?php if (empty($appointments)): ?>
            <div class="service-card" style="text-align: center; padding: 4rem;">
                <i class="fas fa-calendar-times" style="font-size: 5rem; color: #ddd; margin-bottom: 2rem;"></i>
                <p style="font-size: 1.6rem; color: #666;">Bạn chưa có lịch hẹn nào</p>
                <a href="<?= site_url('customer/booking') ?>" class="btn-submit" style="text-decoration: none; display: inline-block; margin-top: 2rem;">
                    Đặt lịch ngay
                </a>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <thead>
                        <tr style="background: var(--primary-color); color: #fff;">
                            <th style="padding: 1.5rem; text-align: left;">Ngày giờ</th>
                            <th style="padding: 1.5rem; text-align: left;">Thú cưng</th>
                            <th style="padding: 1.5rem; text-align: left;">Loại</th>
                            <th style="padding: 1.5rem; text-align: left;">Bác sĩ</th>
                            <th style="padding: 1.5rem; text-align: left;">Dịch vụ</th>
                            <th style="padding: 1.5rem; text-align: left;">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $apt): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 1.5rem;">
                                    <?= date('d/m/Y H:i', strtotime($apt['appointment_date'])) ?>
                                </td>
                                <td style="padding: 1.5rem;"><?= esc($apt['pet_name'] ?? 'N/A') ?></td>
                                <td style="padding: 1.5rem;"><?= esc($apt['appointment_type']) ?></td>
                                <td style="padding: 1.5rem;"><?= esc($apt['doctor_name'] ?? 'Chưa chỉ định') ?></td>
                                <td style="padding: 1.5rem;"><?= esc($apt['service_name'] ?? 'N/A') ?></td>
                                <td style="padding: 1.5rem;">
                                    <?php
                                    $statusColors = [
                                        'pending' => ['bg' => '#fff3cd', 'text' => '#856404', 'label' => 'Chờ xác nhận'],
                                        'confirmed' => ['bg' => '#d4edda', 'text' => '#155724', 'label' => 'Đã xác nhận'],
                                        'completed' => ['bg' => '#d1ecf1', 'text' => '#0c5460', 'label' => 'Hoàn thành'],
                                        'cancelled' => ['bg' => '#f8d7da', 'text' => '#721c24', 'label' => 'Đã hủy']
                                    ];
                                    $status = $apt['status'] ?? 'pending';
                                    $color = $statusColors[$status] ?? $statusColors['pending'];
                                    ?>
                                    <span style="padding: 0.5rem 1rem; border-radius: 6px; font-size: 1.2rem;
                                        background: <?= $color['bg'] ?>; color: <?= $color['text'] ?>;">
                                        <?= $color['label'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= view('layouts/customer_footer', ['settings' => $settings ?? []]) ?>
