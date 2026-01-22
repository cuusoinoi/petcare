<?= view('layouts/customer_header', ['settings' => $settings ?? []]) ?>

<section class="section">
    <div class="container">
        <h2 class="section-title">Thông tin cá nhân</h2>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div style="background: #efe; color: #3c3; padding: 1rem; border-radius: 6px; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="customer-form" style="max-width: 600px;">
            <form method="POST" action="<?= site_url('customer/dashboard/profile') ?>">
                <div class="form-group">
                    <label for="customer_name">Họ và tên</label>
                    <input type="text" id="customer_name" name="customer_name" 
                           value="<?= esc($customer['customer_name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="customer_phone_number">Số điện thoại</label>
                    <input type="tel" id="customer_phone_number" name="customer_phone_number" 
                           value="<?= esc($customer['customer_phone_number'] ?? '') ?>" 
                           readonly style="background: #f5f5f5;">
                    <small style="color: #666;">Số điện thoại không thể thay đổi</small>
                </div>

                <div class="form-group">
                    <label for="customer_email">Email</label>
                    <input type="email" id="customer_email" name="customer_email" 
                           value="<?= esc($customer['customer_email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="customer_address">Địa chỉ</label>
                    <textarea id="customer_address" name="customer_address" rows="3"><?= esc($customer['customer_address'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn-submit">Cập nhật thông tin</button>
            </form>
        </div>
    </div>
</section>

<?= view('layouts/customer_footer', ['settings' => $settings ?? []]) ?>
