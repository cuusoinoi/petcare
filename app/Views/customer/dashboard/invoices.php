<?= view('layouts/customer_header', ['settings' => $settings ?? []]) ?>

<section class="section">
    <div class="container">
        <h2 class="section-title">Hóa đơn</h2>

        <?php if (empty($invoices)): ?>
            <div class="service-card" style="text-align: center; padding: 4rem;">
                <i class="fas fa-receipt" style="font-size: 5rem; color: #ddd; margin-bottom: 2rem;"></i>
                <p style="font-size: 1.6rem; color: #666;">Chưa có hóa đơn nào</p>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <thead>
                        <tr style="background: var(--primary-color); color: #fff;">
                            <th style="padding: 1.5rem; text-align: left;">Mã hóa đơn</th>
                            <th style="padding: 1.5rem; text-align: left;">Thú cưng</th>
                            <th style="padding: 1.5rem; text-align: left;">Ngày lập</th>
                            <th style="padding: 1.5rem; text-align: right;">Tổng tiền</th>
                            <th style="padding: 1.5rem; text-align: center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoices as $invoice): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 1.5rem;">
                                    #<?= str_pad($invoice['invoice_id'], 6, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td style="padding: 1.5rem;"><?= esc($invoice['pet_name'] ?? 'N/A') ?></td>
                                <td style="padding: 1.5rem;">
                                    <?= date('d/m/Y', strtotime($invoice['invoice_date'])) ?>
                                </td>
                                <td style="padding: 1.5rem; text-align: right; font-weight: 600; color: var(--primary-color);">
                                    <?= number_format($invoice['total_amount']) ?> VNĐ
                                </td>
                                <td style="padding: 1.5rem; text-align: center;">
                                    <a href="<?= site_url('customer/dashboard/invoices/view/' . $invoice['invoice_id']) ?>" 
                                       style="color: var(--primary-color); text-decoration: none; font-weight: 600; margin-right: 1rem;">
                                        <i class="fas fa-eye"></i> Xem chi tiết
                                    </a>
                                    <a href="<?= site_url('admin/print/invoice/' . $invoice['invoice_id']) ?>" 
                                       target="_blank" 
                                       style="color: var(--primary-color); text-decoration: none; font-weight: 600;">
                                        <i class="fas fa-print"></i> In
                                    </a>
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
