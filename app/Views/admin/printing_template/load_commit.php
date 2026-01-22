<div class="commitment-sheet">
    <div class="commitment-sheet__header" style="text-align: center; margin-bottom: 20px;">
        <h2 style="font-size: 20px; text-transform: uppercase; margin-bottom: 5px;">GIẤY CAM KẾT LƯU CHUỒNG</h2>
        <div style="font-weight: bold; font-size: 18px;"><?= esc($settings['clinic_name'] ?? 'UIT PETCARE') ?></div>
        <div style="font-size: 13px;">
            Đ/c: <?= esc($settings['clinic_address_1'] ?? '') ?> • ĐT: <?= esc($settings['phone_number_1'] ?? '') ?><?= !empty($settings['phone_number_2']) ? ', ' . esc($settings['phone_number_2']) : '' ?>
        </div>
        <div style="margin-top: 10px;">
            Ngày <?= date("d/m/Y", strtotime($invoice['invoice_date'])) ?>
        </div>
    </div>

    <ol style="padding-left: 20px; line-height: 1.8;">
        <li style="margin-bottom: 15px;">
            <div style="font-weight: bold; text-decoration: underline;">THÔNG TIN CÁC BÊN</div>
            <div>- Bên A (Phòng khám): <?= esc($settings['clinic_name'] ?? '') ?> • Người đại diện: <?= esc($settings['representative_name'] ?? '') ?></div>
            <div>- Bên B (Chủ nuôi): <?= esc($customer['customer_name'] ?? '') ?>
                • CCCD: <?= esc($customer['customer_identity_card'] ?? '-') ?>
                • SĐT: <?= esc($customer['customer_phone_number'] ?? '') ?>
                • Đ/c: <?= esc($customer['customer_address'] ?? '-') ?></div>
        </li>

        <li style="margin-bottom: 15px;">
            <div style="font-weight: bold; text-decoration: underline;">THÔNG TIN THÚ CƯNG</div>
            <div>- Tên thú cưng: <?= esc($pet['pet_name'] ?? '') ?>
                • Loài/giống: <?= !empty($pet['pet_species']) ? esc($pet['pet_species']) : '-' ?>
                • Giới tính: <?= ($pet['pet_gender'] ?? 0) == 1 ? 'Cái' : 'Đực' ?></div>
            <div>- Tuổi/ngày sinh: <?= !empty($pet['pet_dob']) ? esc($pet['pet_dob']) : '-' ?>
                • Cân nặng: <?= !empty($pet['pet_weight']) ? esc($pet['pet_weight']) . 'kg' : '-' ?>
                • Đã triệt sản: <?= ($pet['pet_sterilization'] ?? 0) ? "Có" : "Không" ?></div>
            <div>- Đặc điểm/dị ứng: <?= !empty($pet['pet_characteristic']) || !empty($pet['pet_drug_allergy']) ? esc(($pet['pet_characteristic'] ?? '') . (!empty($pet['pet_characteristic']) && !empty($pet['pet_drug_allergy']) ? ' • ' : '') . ($pet['pet_drug_allergy'] ?? '')) : '-' ?></div>
        </li>

        <li style="margin-bottom: 15px;">
            <div style="font-weight: bold; text-decoration: underline;">THỜI GIAN LƯU CHUỒNG & DỊCH VỤ</div>
            <div>- Thời gian: từ <?= date("d/m/Y H:i", strtotime($enclosure['check_in_date'])) ?> đến <?= !empty($enclosure['check_out_date']) ? date("d/m/Y H:i", strtotime($enclosure['check_out_date'])) : "-" ?></div>
            <div>- Ghi chú (dịch vụ, đồ gửi kèm...): <?= !empty($enclosure['pet_enclosure_note']) ? esc($enclosure['pet_enclosure_note']) : 'Không có' ?></div>
        </li>

        <li style="margin-bottom: 15px;">
            <div style="font-weight: bold; text-decoration: underline;">XỬ LÝ TÌNH HUỐNG KHẨN CẤP</div>
            <div>- Khi thú cưng nguy cấp, Bên A ưu tiên liên hệ Bên B. Nếu không được, Bên A được quyền cấp cứu kịp thời.</div>
            <div>- Giới hạn chi phí cấp cứu được phép: <?= number_format($enclosure['emergency_limit'] ?? 0, 0, ",", ".") ?> đ.</div>
        </li>

        <li style="margin-bottom: 15px;">
            <div style="font-weight: bold; text-decoration: underline;">PHÍ & THANH TOÁN</div>
            <div>- Đơn giá: <?= number_format($enclosure['daily_rate'] ?? 0, 0, ",", ".") ?> đ/ngày. Phí phát sinh theo bảng giá/thỏa thuận.</div>
            <div>- Đã cọc: <?= number_format($enclosure['deposit'] ?? 0, 0, ",", ".") ?> đ. Thanh toán đủ khi nhận thú cưng.</div>
            <div>- Nhận trễ giờ quy định có thể phụ thu: <?= number_format($settings['overtime_fee_per_hour'] ?? 0, 0, ",", ".") ?> đ/giờ.</div>
        </li>
    </ol>

    <div style="font-style: italic; margin: 20px 0;">
        * Bên B đã đọc, hiểu và đồng ý với các điều khoản về rủi ro, hành vi, an toàn... của phòng khám.
    </div>

    <table style="width: 100%; margin-top: 30px; text-align: center;">
        <tr>
            <td style="width: 50%; padding: 10px;">
                <strong>BÊN A (Phòng khám)</strong><br>(Ký, ghi rõ họ tên)<br><br><br><br>
            </td>
            <td style="width: 50%; padding: 10px;">
                <strong>BÊN B (Chủ nuôi)</strong><br>(Ký, ghi rõ họ tên)<br><br><br><br>
            </td>
        </tr>
    </table>

    <div style="text-align: right; margin-top: 20px; font-style: italic;">
        <?= esc($settings['signing_place'] ?? '') ?>, ngày <?= date("d") ?> tháng <?= date("m") ?> năm <?= date("Y") ?>
    </div>
</div>
