<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('admin_assets/images/logo.png') ?>">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 14px; line-height: 1.6; padding: 20px; }
        .print-container { max-width: 800px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { font-size: 20px; text-transform: uppercase; margin-bottom: 5px; }
        .header p { font-size: 12px; }
        .title { text-align: center; margin: 20px 0; }
        .title h2 { font-size: 24px; text-transform: uppercase; }
        .info-section { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info-left, .info-right { width: 48%; }
        .info-row { display: flex; margin-bottom: 5px; }
        .info-label { font-weight: bold; min-width: 120px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        td.number { text-align: right; }
        .total-row { font-weight: bold; font-size: 16px; }
        .footer { margin-top: 30px; }
        .signature { display: flex; justify-content: space-between; margin-top: 50px; }
        .signature-box { text-align: center; width: 45%; }
        .signature-box p { margin-bottom: 60px; }
        .no-print { margin-bottom: 20px; text-align: center; }
        .btn-print { padding: 10px 30px; background: #3498db; color: #fff; border: none; cursor: pointer; font-size: 16px; border-radius: 5px; }
        .btn-print:hover { background: #2980b9; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ In hóa đơn</button>
        <button class="btn-print" onclick="window.close()" style="background: #95a5a6;">✕ Đóng</button>
    </div>
    
    <div class="print-container">
        <div class="header">
            <h1><?= esc($settings['clinic_name'] ?? 'UIT PETCARE') ?></h1>
            <p><?= esc($settings['clinic_address_1'] ?? '') ?></p>
            <p>ĐT: <?= esc($settings['phone_number_1'] ?? '') ?> <?= !empty($settings['phone_number_2']) ? ' - ' . esc($settings['phone_number_2']) : '' ?></p>
        </div>

        <div class="title">
            <h2>HÓA ĐƠN THANH TOÁN</h2>
            <p>Số: <?= str_pad($invoice['invoice_id'], 6, '0', STR_PAD_LEFT) ?></p>
        </div>

        <div class="info-section">
            <div class="info-left">
                <div class="info-row">
                    <span class="info-label">Khách hàng:</span>
                    <span><?= esc($customer['customer_name'] ?? '') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Điện thoại:</span>
                    <span><?= esc($customer['customer_phone_number'] ?? '') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Địa chỉ:</span>
                    <span><?= esc($customer['customer_address'] ?? '') ?></span>
                </div>
            </div>
            <div class="info-right">
                <div class="info-row">
                    <span class="info-label">Thú cưng:</span>
                    <span><?= esc($pet['pet_name'] ?? '') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày lập:</span>
                    <span><?= date('d/m/Y', strtotime($invoice['invoice_date'])) ?></span>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">STT</th>
                    <th>Dịch vụ</th>
                    <th style="width: 80px;">Số lượng</th>
                    <th style="width: 120px;">Đơn giá</th>
                    <th style="width: 120px;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($details)): ?>
                    <?php $stt = 1; foreach ($details as $item): ?>
                        <tr>
                            <td class="number"><?= $stt++ ?></td>
                            <td><?= esc($item['service_name'] ?? 'Dịch vụ') ?></td>
                            <td class="number"><?= $item['quantity'] ?></td>
                            <td class="number"><?= number_format($item['unit_price']) ?></td>
                            <td class="number"><?= number_format($item['total_price'] ?? ($item['quantity'] * $item['unit_price'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center;">Không có dịch vụ</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;">Tổng tiền dịch vụ:</td>
                    <td class="number"><?= number_format($invoice['subtotal'] ?? 0) ?> VNĐ</td>
                </tr>
                <?php if (!empty($invoice['discount']) && $invoice['discount'] > 0): ?>
                <tr>
                    <td colspan="4" style="text-align: right;">Giảm giá:</td>
                    <td class="number">-<?= number_format($invoice['discount']) ?> VNĐ</td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($invoice['deposit']) && $invoice['deposit'] > 0): ?>
                <tr>
                    <td colspan="4" style="text-align: right;">Đã đặt cọc:</td>
                    <td class="number">-<?= number_format($invoice['deposit']) ?> VNĐ</td>
                </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td colspan="4" style="text-align: right;">CÒN PHẢI THANH TOÁN:</td>
                    <td class="number"><?= number_format($invoice['total_amount']) ?> VNĐ</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p><strong>Phương thức thanh toán:</strong> Tiền mặt</p>
        </div>

        <div class="signature">
            <div class="signature-box">
                <p>Khách hàng</p>
                <p><em>(Ký, ghi rõ họ tên)</em></p>
            </div>
            <div class="signature-box">
                <p><?= esc($settings['signing_place'] ?? '') ?>, ngày <?= date('d') ?> tháng <?= date('m') ?> năm <?= date('Y') ?></p>
                <p>Người lập phiếu</p>
                <p><em>(Ký, ghi rõ họ tên)</em></p>
            </div>
        </div>
    </div>
</body>
</html>
