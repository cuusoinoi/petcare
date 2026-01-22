<?= view('layouts/customer_header', ['settings' => []]) ?>

<section class="section" style="min-height: 70vh; display: flex; align-items: center;">
    <div class="container">
        <div class="customer-form">
            <h2 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">Đăng ký</h2>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div style="background: #fee; color: #c33; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= site_url('customer/register') ?>" id="registerForm">
                <div class="form-group">
                    <label for="name">Họ và tên <span style="color: red;">*</span></label>
                    <input type="text" id="name" name="name" required 
                           value="<?= old('name') ?>"
                           placeholder="Nhập họ và tên">
                </div>

                <div class="form-group">
                    <label for="phone">Số điện thoại <span style="color: red;">*</span></label>
                    <input type="tel" id="phone" name="phone" required 
                           value="<?= old('phone') ?>"
                           placeholder="Nhập số điện thoại" 
                           pattern="[0-9]{10,11}">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?= old('email') ?>"
                           placeholder="Nhập email (tùy chọn)">
                </div>

                <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <textarea id="address" name="address" rows="3" 
                              placeholder="Nhập địa chỉ (tùy chọn)"><?= old('address') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="otp">Mã OTP <span style="color: red;">*</span></label>
                    <input type="text" id="otp" name="otp" required 
                           value="<?= old('otp') ?>"
                           placeholder="Nhập mã OTP (mặc định: 123456)" 
                           maxlength="6">
                    <small style="color: #666; margin-top: 0.5rem; display: block;">
                        Mã OTP mặc định: <strong>123456</strong>
                    </small>
                </div>

                <button type="submit" class="btn-submit">Đăng ký</button>
            </form>

            <p style="text-align: center; margin-top: 2rem; color: #666;">
                Đã có tài khoản? 
                <a href="<?= site_url('customer/login') ?>" style="color: var(--primary-color); font-weight: 600;">
                    Đăng nhập ngay
                </a>
            </p>
        </div>
    </div>
</section>

<?= view('layouts/customer_footer', ['settings' => []]) ?>
