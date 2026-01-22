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
        .section-content { min-height: 40px; }
        .vitals { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 15px; }
        .vital-box { text-align: center; padding: 10px; border: 1px solid #ddd; }
        .vital-value { font-size: 18px; font-weight: bold; color: #2980b9; }
        .vital-label { font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
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
        <button class="btn-print" onclick="window.print()">🖨️ In phiếu điều trị</button>
        <button class="btn-print" onclick="window.close()" style="background: #95a5a6;">✕ Đóng</button>
    </div>
    
    <div class="print-container">
        <div class="header">
            <h1><?= esc($settings['clinic_name'] ?? 'UIT PETCARE') ?></h1>
            <p><?= esc($settings['clinic_address_1'] ?? '') ?></p>
            <p>ĐT: <?= esc($settings['phone_number_1'] ?? '') ?></p>
        </div>

        <div class="title">
            <h2>PHIẾU ĐIỀU TRỊ</h2>
            <p>Liệu trình #<?= $course['treatment_course_id'] ?> - Buổi <?= date('d/m/Y H:i', strtotime($session['treatment_session_datetime'])) ?></p>
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
                        <span class="info-label">Bác sĩ điều trị:</span>
                        <span><?= esc($doctor['doctor_name'] ?? '') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="vitals">
            <div class="vital-box">
                <div class="vital-value"><?= $session['temperature'] ?? '-' ?>°C</div>
                <div class="vital-label">Nhiệt độ</div>
            </div>
            <div class="vital-box">
                <div class="vital-value"><?= $session['weight'] ?? '-' ?> kg</div>
                <div class="vital-label">Cân nặng</div>
            </div>
            <div class="vital-box">
                <div class="vital-value"><?= $session['pulse_rate'] ?? '-' ?></div>
                <div class="vital-label">Mạch</div>
            </div>
            <div class="vital-box">
                <div class="vital-value"><?= $session['respiratory_rate'] ?? '-' ?></div>
                <div class="vital-label">Nhịp thở</div>
            </div>
        </div>

        <?php if ($diagnosis): ?>
        <div class="section">
            <div class="section-title">Chẩn đoán</div>
            <div class="section-content">
                <p><strong>Tên:</strong> <?= esc($diagnosis['diagnosis_name'] ?? '') ?></p>
                <p><strong>Loại:</strong> <?= esc($diagnosis['diagnosis_type'] ?? '') ?></p>
                <p><strong>Xét nghiệm:</strong> <?= nl2br(esc($diagnosis['clinical_tests'] ?? '')) ?></p>
                <p><strong>Ghi chú:</strong> <?= nl2br(esc($diagnosis['notes'] ?? '')) ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($prescriptions)): ?>
        <div class="section">
            <div class="section-title">Đơn thuốc</div>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Thuốc</th>
                        <th>Liều lượng</th>
                        <th>Tần suất</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $stt = 1; foreach ($prescriptions as $p): ?>
                        <tr>
                            <td><?= $stt++ ?></td>
                            <td><?= esc($p['medicine_name']) ?> (<?= esc($p['medicine_route']) ?>)</td>
                            <td><?= esc($p['dosage']) ?> <?= esc($p['unit']) ?></td>
                            <td><?= esc($p['frequency']) ?></td>
                            <td><?= esc($p['notes']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?php if ($session['overall_notes']): ?>
        <div class="section">
            <div class="section-title">Ghi chú tổng quát</div>
            <div class="section-content"><?= nl2br(esc($session['overall_notes'])) ?></div>
        </div>
        <?php endif; ?>

        <div class="signature">
            <div class="signature-box">
                <p>Khách hàng</p>
                <p><em>(Ký, ghi rõ họ tên)</em></p>
            </div>
            <div class="signature-box">
                <p><?= esc($settings['signing_place'] ?? '') ?>, ngày <?= date('d') ?> tháng <?= date('m') ?> năm <?= date('Y') ?></p>
                <p>Bác sĩ điều trị</p>
                <p><em>(Ký, ghi rõ họ tên)</em></p>
            </div>
        </div>
    </div>
</body>
</html>
