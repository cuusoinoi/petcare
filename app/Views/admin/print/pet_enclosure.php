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
        .info-section { margin-bottom: 20px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .info-row { display: flex; margin-bottom: 8px; }
        .info-label { font-weight: bold; min-width: 150px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; background: #f9f9f9; }
        .section-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; }
        .total-box { background: #e8f4fd; padding: 15px; margin: 20px 0; text-align: center; }
        .total-box .amount { font-size: 28px; font-weight: bold; color: #2980b9; }
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
        <button class="btn-print" onclick="window.print()">🖨️ In phiếu lưu chuồng</button>
        <button class="btn-print" onclick="window.close()" style="background: #95a5a6;">✕ Đóng</button>
    </div>
    
    <div class="print-container">
        <div class="header">
            <h1><?= esc($settings['clinic_name'] ?? 'UIT PETCARE') ?></h1>
            <p><?= esc($settings['clinic_address_1'] ?? '') ?></p>
            <p>ĐT: <?= esc($settings['phone_number_1'] ?? '') ?></p>
        </div>

        <div class="title">
            <h2>PHIẾU LƯU CHUỒNG</h2>
            <p>Số: <?= str_pad($enclosure['pet_enclosure_id'], 6, '0', STR_PAD_LEFT) ?></p>
        </div>

        <div class="info-section">
            <div class="info-grid">
                <div>
                    <div class="info-row">
                        <span class="info-label">Chủ thú cưng:</span>
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
                <div>
                    <div class="info-row">
                        <span class="info-label">Tên thú cưng:</span>
                        <span><?= esc($pet['pet_name'] ?? '') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Loài / Giống:</span>
                        <span><?= esc($pet['pet_species'] ?? '') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Thông tin lưu chuồng</div>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Số chuồng:</span>
                    <span><?= $enclosure['pet_enclosure_number'] ?? '' ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày nhận:</span>
                    <span><?= date('d/m/Y H:i', strtotime($enclosure['check_in_date'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày trả (dự kiến):</span>
                    <span><?= $enclosure['check_out_date'] ? date('d/m/Y H:i', strtotime($enclosure['check_out_date'])) : '-' ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Giá ngày:</span>
                    <span><?= number_format($enclosure['daily_rate'] ?? 0) ?> VNĐ</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Đặt cọc:</span>
                    <span><?= number_format($enclosure['deposit'] ?? 0) ?> VNĐ</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Trạng thái:</span>
                    <span><?= esc($enclosure['pet_enclosure_status'] ?? '') ?></span>
                </div>
            </div>
        </div>

        <?php if (!empty($enclosure['pet_enclosure_note'])): ?>
        <div class="section">
            <div class="section-title">Ghi chú</div>
            <p><?= nl2br(esc($enclosure['pet_enclosure_note'])) ?></p>
        </div>
        <?php endif; ?>

        <?php 
        // Tính tổng tiền nếu đã checkout
        $totalAmount = 0;
        if ($enclosure['check_out_date']) {
            $checkIn = new DateTime($enclosure['check_in_date']);
            $checkOut = new DateTime($enclosure['check_out_date']);
            $days = $checkIn->diff($checkOut)->days;
            if ($days < 1) $days = 1;
            $totalAmount = $days * ($enclosure['daily_rate'] ?? 0);
        }
        ?>
        <?php if ($totalAmount > 0): ?>
        <div class="total-box">
            <p>TỔNG TIỀN THANH TOÁN (<?= $days ?> ngày)</p>
            <p class="amount"><?= number_format($totalAmount) ?> VNĐ</p>
        </div>
        <?php endif; ?>

        <div class="signature">
            <div class="signature-box">
                <p>Khách hàng</p>
                <p><em>(Ký, ghi rõ họ tên)</em></p>
            </div>
            <div class="signature-box">
                <p><?= esc($settings['signing_place'] ?? '') ?>, ngày <?= date('d') ?> tháng <?= date('m') ?> năm <?= date('Y') ?></p>
                <p>Nhân viên</p>
                <p><em>(Ký, ghi rõ họ tên)</em></p>
            </div>
        </div>
    </div>
</body>
</html>
