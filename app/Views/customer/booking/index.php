<?= view('layouts/customer_header', ['settings' => $settings ?? []]) ?>

<section class="section">
    <div class="container">
        <h2 class="section-title">Đặt lịch hẹn</h2>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div style="background: #fee; color: #c33; padding: 1rem; border-radius: 6px; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div style="background: #efe; color: #3c3; padding: 1rem; border-radius: 6px; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="customer-form" style="max-width: 700px;">
            <form method="POST" action="<?= site_url('customer/booking/create') ?>">
                <div class="form-group">
                    <label for="pet_id">Chọn thú cưng <span style="color: red;">*</span></label>
                    <select id="pet_id" name="pet_id" required>
                        <option value="">-- Chọn thú cưng --</option>
                        <?php if (!empty($pets)): ?>
                            <?php foreach ($pets as $pet): ?>
                                <option value="<?= $pet['pet_id'] ?>">
                                    <?= esc($pet['pet_name']) ?> - <?= esc($pet['pet_species'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (empty($pets)): ?>
                        <small style="color: #c33;">
                            Bạn chưa có thú cưng. 
                            <a href="<?= site_url('customer/dashboard/pets/add') ?>" style="color: var(--primary-color);">
                                Thêm thú cưng ngay
                            </a>
                        </small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="appointment_type">Loại dịch vụ <span style="color: red;">*</span></label>
                    <select id="appointment_type" name="appointment_type" required>
                        <option value="">-- Chọn loại dịch vụ --</option>
                        <option value="Khám">Khám bệnh</option>
                        <option value="Spa">Spa & Chăm sóc</option>
                        <option value="Tiêm chủng">Tiêm chủng</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="service_id">Dịch vụ cụ thể</label>
                    <select id="service_id" name="service_id">
                        <option value="">-- Chọn dịch vụ (tùy chọn) --</option>
                        <?php if (!empty($services)): ?>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['service_type_id'] ?>">
                                    <?= esc($service['service_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="doctor_id">Chọn bác sĩ</label>
                    <select id="doctor_id" name="doctor_id">
                        <option value="">-- Chọn bác sĩ (tùy chọn) --</option>
                        <?php if (!empty($doctors)): ?>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?= $doctor['doctor_id'] ?>">
                                    <?= esc($doctor['doctor_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="appointment_date">Ngày hẹn <span style="color: red;">*</span></label>
                    <input type="date" id="appointment_date" name="appointment_date" required 
                           min="<?= date('Y-m-d') ?>">
                </div>

                <div class="form-group">
                    <label for="appointment_time">Giờ hẹn <span style="color: red;">*</span></label>
                    <input type="time" id="appointment_time" name="appointment_time" required>
                </div>

                <div class="form-group">
                    <label for="notes">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="4" 
                              placeholder="Nhập ghi chú (nếu có)"></textarea>
                </div>

                <button type="submit" class="btn-submit">Đặt lịch</button>
            </form>
        </div>
    </div>
</section>

<?= view('layouts/customer_footer', ['settings' => $settings ?? []]) ?>
