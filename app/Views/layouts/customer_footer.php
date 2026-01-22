    <footer class="customer-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>UIT Petcare</h3>
                    <p>Phòng khám thú y & Spa thú cưng chuyên nghiệp</p>
                </div>
                <div class="footer-section">
                    <h4>Liên hệ</h4>
                    <p><i class="fas fa-map-marker-alt"></i> <?= esc($settings['clinic_address_1'] ?? '') ?></p>
                    <p><i class="fas fa-phone"></i> <?= esc($settings['phone_number_1'] ?? '') ?></p>
                </div>
                <div class="footer-section">
                    <h4>Giờ làm việc</h4>
                    <p>Thứ 2 - Chủ nhật: 8:00 - 20:00</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> UIT Petcare. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    <script src="<?= base_url('assets/js/customer.js') ?>"></script>
</body>
</html>
