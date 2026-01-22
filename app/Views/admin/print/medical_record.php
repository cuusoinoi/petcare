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
        .info-row { display: flex; margin-bottom: 5px; }
        .info-label { font-weight: bold; min-width: 150px; }
        .section { margin: 20px 0; padding: 10px; border: 1px solid #ddd; }
        .section-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px; }
        .section-content { min-height: 60px; }
        .vitals { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 15px; }
        .vital-box { text-align: center; padding: 10px; border: 1px solid #ddd; }
        .vital-value { font-size: 18px; font-weight: bold; color: #2980b9; }
        .vital-label { font-size: 12px; }
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
        <button class="btn-print" onclick="window.print()">🖨️ In phiếu khám</button>
        <button class="btn-print" onclick="window.close()" style="background: #95a5a6;">✕ Đóng</button>
    </div>
    
    <div class="print-container">
        <div class="header">
            <h1><?= esc($settings['clinic_name'] ?? 'UIT PETCARE') ?></h1>
            <p><?= esc($settings['clinic_address_1'] ?? '') ?></p>
            <p>ĐT: <?= esc($settings['phone_number_1'] ?? '') ?></p>
        </div>

        <div class="title">
            <h2>PHIẾU KHÁM BỆNH</h2>
            <p>Số: <?= str_pad($record['medical_record_id'], 6, '0', STR_PAD_LEFT) ?></p>
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
            <div class="info-grid" style="margin-top: 10px;">
                <div class="info-row">
                    <span class="info-label">Ngày khám:</span>
                    <span><?= date('d/m/Y', strtotime($record['medical_record_visit_date'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Bác sĩ khám:</span>
                    <span><?= esc($doctor['doctor_name'] ?? '') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Loại khám:</span>
                    <span><?= esc($record['medical_record_type'] ?? '') ?></span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Tóm tắt</div>
            <div class="section-content"><?= nl2br(esc($record['medical_record_summary'] ?? 'Không có')) ?></div>
        </div>

        <div class="section">
            <div class="section-title">Chi tiết khám / Điều trị</div>
            <div class="section-content"><?= nl2br(esc($record['medical_record_details'] ?? 'Không có')) ?></div>
        </div>

        <div class="signature">
            <div class="signature-box">
                <p>Khách hàng</p>
                <p><em>(Ký, ghi rõ họ tên)</em></p>
            </div>
            <div class="signature-box">
                <p><?= esc($settings['signing_place'] ?? '') ?>, ngày <?= date('d') ?> tháng <?= date('m') ?> năm <?= date('Y') ?></p>
                <p>Bác sĩ khám</p>
                <p><em>(Ký, ghi rõ họ tên)</em></p>
            </div>
        </div>
    </div>
</body>
</html>
