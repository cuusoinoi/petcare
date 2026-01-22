<?= view('layouts/customer_header', ['settings' => $settings ?? []]) ?>

<section class="section">
    <div class="container">
        <div style="margin-bottom: 2rem;">
            <a href="<?= site_url('customer/dashboard/invoices') ?>" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách hóa đơn
            </a>
        </div>

        <div class="service-card" style="padding: 2rem;">
            <h2 class="section-title" style="margin-bottom: 2rem;">Chi tiết hóa đơn #<?= str_pad($invoice['invoice_id'], 6, '0', STR_PAD_LEFT) ?></h2>

            <!-- Thông tin hóa đơn -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.4rem;">Thông tin khách hàng</h3>
                    <p><strong>Họ tên:</strong> <?= esc($invoice['customer_name']) ?></p>
                    <p><strong>Điện thoại:</strong> <?= esc($invoice['customer_phone_number']) ?></p>
                    <p><strong>Địa chỉ:</strong> <?= esc($invoice['customer_address'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.4rem;">Thông tin thú cưng</h3>
                    <p><strong>Tên thú cưng:</strong> <?= esc($invoice['pet_name']) ?></p>
                    <p><strong>Loài/Giống:</strong> <?= esc($invoice['pet_species'] ?? 'N/A') ?></p>
                    <p><strong>Ngày lập:</strong> <?= date('d/m/Y', strtotime($invoice['invoice_date'])) ?></p>
                </div>
            </div>

            <!-- Chi tiết dịch vụ -->
            <h3 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.4rem;">Chi tiết dịch vụ</h3>
            <div style="overflow-x: auto; margin-bottom: 2rem;">
                <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden;">
                    <thead>
                        <tr style="background: var(--primary-color); color: #fff;">
                            <th style="padding: 1rem; text-align: left;">STT</th>
                            <th style="padding: 1rem; text-align: left;">Dịch vụ</th>
                            <th style="padding: 1rem; text-align: center;">Số lượng</th>
                            <th style="padding: 1rem; text-align: right;">Đơn giá</th>
                            <th style="padding: 1rem; text-align: right;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($invoiceDetails)): ?>
                            <?php $stt = 1; foreach ($invoiceDetails as $detail): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 1rem;"><?= $stt++ ?></td>
                                    <td style="padding: 1rem;"><?= esc($detail['service_name'] ?? 'Dịch vụ') ?></td>
                                    <td style="padding: 1rem; text-align: center;"><?= $detail['quantity'] ?></td>
                                    <td style="padding: 1rem; text-align: right;"><?= number_format($detail['unit_price'], 0, ',', '.') ?> đ</td>
                                    <td style="padding: 1rem; text-align: right; font-weight: 600;"><?= number_format($detail['total_price'], 0, ',', '.') ?> đ</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="padding: 2rem; text-align: center; color: #666;">Không có chi tiết dịch vụ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tổng kết -->
            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span><strong>Tổng tiền dịch vụ:</strong></span>
                    <span><strong><?= number_format($invoice['subtotal'], 0, ',', '.') ?> đ</strong></span>
                </div>
                <?php if ($invoice['discount'] > 0): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #e74c3c;">
                        <span>Giảm giá:</span>
                        <span>- <?= number_format($invoice['discount'], 0, ',', '.') ?> đ</span>
                    </div>
                <?php endif; ?>
                <?php if ($invoice['deposit'] > 0): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #27ae60;">
                        <span>Đã đặt cọc:</span>
                        <span>- <?= number_format($invoice['deposit'], 0, ',', '.') ?> đ</span>
                    </div>
                <?php endif; ?>
                <div style="display: flex; justify-content: space-between; padding-top: 1rem; border-top: 2px solid var(--primary-color); margin-top: 1rem;">
                    <span style="font-size: 1.2rem; font-weight: 700; color: var(--primary-color);">Còn phải thanh toán:</span>
                    <span style="font-size: 1.2rem; font-weight: 700; color: var(--primary-color);"><?= number_format($invoice['total_amount'], 0, ',', '.') ?> đ</span>
                </div>
            </div>

            <!-- Nút in -->
            <div style="text-align: center;">
                <a href="<?= site_url('admin/print/invoice/' . $invoice['invoice_id']) ?>" 
                   target="_blank" 
                   style="display: inline-block; padding: 1rem 2rem; background: var(--primary-color); color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; transition: background 0.3s;">
                    <i class="fas fa-print"></i> In hóa đơn
                </a>
            </div>
        </div>
    </div>
</section>

<?= view('layouts/customer_footer', ['settings' => $settings ?? []]) ?>
