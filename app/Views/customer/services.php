<?= view('layouts/customer_header', ['settings' => $settings]) ?>

<?php
// Helper function để lấy icon phù hợp với dịch vụ
function getServiceIcon($serviceName) {
    $serviceName = mb_strtolower($serviceName, 'UTF-8');
    
    if (strpos($serviceName, 'lưu chuồng') !== false || strpos($serviceName, 'lưu trú') !== false) {
        return 'fa-home';
    } elseif (strpos($serviceName, 'phụ thu') !== false || strpos($serviceName, 'trễ giờ') !== false) {
        return 'fa-clock';
    } elseif (strpos($serviceName, 'đồ gửi') !== false || strpos($serviceName, 'gửi kèm') !== false) {
        return 'fa-box';
    } elseif (strpos($serviceName, 'tắm') !== false && strpos($serviceName, 'dưỡng') === false) {
        return 'fa-shower';
    } elseif (strpos($serviceName, 'cắt tỉa') !== false || strpos($serviceName, 'cắt lông') !== false) {
        return 'fa-cut';
    } elseif (strpos($serviceName, 'khám') !== false || strpos($serviceName, 'điều trị') !== false) {
        return 'fa-stethoscope';
    } elseif (strpos($serviceName, 'tiêm') !== false || strpos($serviceName, 'vaccine') !== false || strpos($serviceName, 'vắc xin') !== false) {
        return 'fa-syringe';
    } elseif (strpos($serviceName, 'vệ sinh tai') !== false || strpos($serviceName, 'tai') !== false) {
        return 'fa-headphones';
    } elseif (strpos($serviceName, 'răng') !== false || strpos($serviceName, 'miệng') !== false) {
        return 'fa-tooth';
    } elseif (strpos($serviceName, 'massage') !== false || strpos($serviceName, 'thư giãn') !== false) {
        return 'fa-spa';
    } elseif (strpos($serviceName, 'huấn luyện') !== false) {
        return 'fa-graduation-cap';
    } elseif (strpos($serviceName, 'thức ăn') !== false || strpos($serviceName, 'ăn') !== false) {
        return 'fa-utensils';
    } elseif (strpos($serviceName, 'cắt móng') !== false || strpos($serviceName, 'móng') !== false) {
        return 'fa-cut';
    } elseif (strpos($serviceName, 'dưỡng lông') !== false || strpos($serviceName, 'dưỡng') !== false) {
        return 'fa-spa';
    } elseif (strpos($serviceName, 'tẩy giun') !== false || strpos($serviceName, 'giun') !== false) {
        return 'fa-pills';
    } elseif (strpos($serviceName, 'phẫu thuật') !== false || strpos($serviceName, 'phẫu') !== false) {
        return 'fa-user-md';
    } elseif (strpos($serviceName, 'x-quang') !== false || strpos($serviceName, 'xray') !== false || strpos($serviceName, 'x ray') !== false) {
        return 'fa-x-ray';
    } elseif (strpos($serviceName, 'siêu âm') !== false || strpos($serviceName, 'ultrasound') !== false) {
        return 'fa-wave-square';
    } elseif (strpos($serviceName, 'tư vấn') !== false || strpos($serviceName, 'dinh dưỡng') !== false) {
        return 'fa-clipboard-list';
    } elseif (strpos($serviceName, 'xét nghiệm') !== false || strpos($serviceName, 'chẩn đoán') !== false) {
        return 'fa-flask';
    } elseif (strpos($serviceName, 'vận chuyển') !== false || strpos($serviceName, 'tại nhà') !== false) {
        return 'fa-truck-medical';
    } elseif (strpos($serviceName, 'cấp cứu') !== false || strpos($serviceName, 'emergency') !== false) {
        return 'fa-ambulance';
    } elseif (strpos($serviceName, 'thẩm mỹ') !== false) {
        return 'fa-spa';
    } else {
        return 'fa-paw'; // Icon mặc định
    }
}
?>

<section class="section">
    <div class="container">
        <h2 class="section-title">Dịch vụ & Bảng giá</h2>
        <p class="section-subtitle">Các dịch vụ chăm sóc thú cưng của chúng tôi</p>
        
        <div class="services-grid">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas <?= getServiceIcon($service['service_name']) ?>"></i>
                        </div>
                        <h3><?= esc($service['service_name']) ?></h3>
                        <p><?= esc($service['description'] ?? 'Dịch vụ chuyên nghiệp') ?></p>
                        <?php if (isset($service['price']) && $service['price'] > 0): ?>
                            <div class="service-price">
                                <strong><?= number_format($service['price'], 0, ',', '.') ?> VNĐ</strong>
                            </div>
                        <?php else: ?>
                            <div class="service-price">
                                <strong style="color: #999;">Liên hệ</strong>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; grid-column: 1 / -1;">Chưa có dịch vụ nào</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?= view('layouts/customer_footer', ['settings' => $settings]) ?>
