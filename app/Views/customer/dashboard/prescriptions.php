<?= view('layouts/customer_header', ['settings' => $settings ?? []]) ?>

<section class="section">
    <div class="container">
        <h2 class="section-title">Đơn thuốc</h2>

        <div class="customer-form" style="max-width: 400px; margin-bottom: 3rem;">
            <form method="GET" action="<?= site_url('customer/dashboard/prescriptions') ?>">
                <div class="form-group">
                    <label for="pet_id">Chọn thú cưng</label>
                    <select id="pet_id" name="pet_id" onchange="this.form.submit()">
                        <option value="">-- Tất cả thú cưng --</option>
                        <?php foreach ($pets as $pet): ?>
                            <option value="<?= $pet['pet_id'] ?>" 
                                    <?= ($selectedPetId == $pet['pet_id']) ? 'selected' : '' ?>>
                                <?= esc($pet['pet_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <?php if (empty($prescriptions)): ?>
            <div class="service-card" style="text-align: center; padding: 4rem;">
                <i class="fas fa-pills" style="font-size: 5rem; color: #ddd; margin-bottom: 2rem;"></i>
                <p style="font-size: 1.6rem; color: #666;">Chưa có đơn thuốc nào</p>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <thead>
                        <tr style="background: var(--primary-color); color: #fff;">
                            <th style="padding: 1.5rem; text-align: left;">Thuốc</th>
                            <th style="padding: 1.5rem; text-align: left;">Liều lượng</th>
                            <th style="padding: 1.5rem; text-align: left;">Tần suất</th>
                            <th style="padding: 1.5rem; text-align: left;">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prescriptions as $pres): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 1.5rem;">
                                    <strong><?= esc($pres['medicine_name']) ?></strong><br>
                                    <small style="color: #666;"><?= esc($pres['medicine_route']) ?></small>
                                </td>
                                <td style="padding: 1.5rem;">
                                    <?= esc($pres['dosage']) ?> <?= esc($pres['unit']) ?>
                                </td>
                                <td style="padding: 1.5rem;"><?= esc($pres['frequency'] ?? 'N/A') ?></td>
                                <td style="padding: 1.5rem;"><?= esc($pres['notes'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= view('layouts/customer_footer', ['settings' => $settings ?? []]) ?>
