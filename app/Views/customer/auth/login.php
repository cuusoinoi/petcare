<?= view('layouts/customer_header', ['settings' => []]) ?>

<section class="section" style="min-height: 70vh; display: flex; align-items: center;">
    <div class="container">
        <div class="customer-form">
            <h2 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">Đăng nhập</h2>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div style="background: #fee; color: #c33; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div style="background: #efe; color: #3c3; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= site_url('customer/login') ?>">
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="tel" id="phone" name="phone" required 
                           placeholder="Nhập số điện thoại" 
                           pattern="[0-9]{10,11}">
                </div>

                <div class="form-group">
                    <label for="otp">Mã OTP</label>
                    <input type="text" id="otp" name="otp" required 
                           placeholder="Nhập mã OTP (mặc định: 123456)" 
                           maxlength="6">
                    <small style="color: #666; margin-top: 0.5rem; display: block;">
                        Mã OTP mặc định: <strong>123456</strong>
                    </small>
                </div>

                <button type="submit" class="btn-submit">Đăng nhập</button>
            </form>

            <p style="text-align: center; margin-top: 2rem; color: #666;">
                Chưa có tài khoản? 
                <a href="<?= site_url('customer/register') ?>" style="color: var(--primary-color); font-weight: 600;">
                    Đăng ký ngay
                </a>
            </p>
            
            <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e0e0e0;">
                <a href="<?= site_url('admin') ?>" class="btn-admin" style="display: inline-block;">
                    <i class="fas fa-user-shield"></i> Đăng nhập Admin
                </a>
            </div>
        </div>
    </div>
</section>

<?= view('layouts/customer_footer', ['settings' => []]) ?>
