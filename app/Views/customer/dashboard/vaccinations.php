<?= view('layouts/customer_header', ['settings' => $settings ?? []]) ?>

<section class="section">
    <div class="container">
        <h2 class="section-title">Lịch tiêm chủng</h2>

        <div class="customer-form" style="max-width: 400px; margin-bottom: 3rem;">
            <form method="GET" action="<?= site_url('customer/dashboard/vaccinations') ?>">
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

        <?php if (empty($vaccinations)): ?>
            <div class="service-card" style="text-align: center; padding: 4rem;">
                <i class="fas fa-syringe" style="font-size: 5rem; color: #ddd; margin-bottom: 2rem;"></i>
                <p style="font-size: 1.6rem; color: #666;">Chưa có lịch tiêm chủng</p>
            </div>
        <?php else: ?>
            <div class="services-grid">
                <?php foreach ($vaccinations as $vac): ?>
                    <div class="service-card">
                        <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                            <i class="fas fa-syringe"></i> <?= esc($vac['vaccine_name']) ?>
                        </h3>
                        <p><strong>Ngày tiêm:</strong> <?= date('d/m/Y', strtotime($vac['vaccination_date'])) ?></p>
                        <?php if ($vac['next_vaccination_date']): ?>
                            <p style="color: var(--primary-color); font-weight: 600;">
                                <i class="fas fa-calendar-check"></i> 
                                Mũi tiếp theo: <?= date('d/m/Y', strtotime($vac['next_vaccination_date'])) ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($vac['notes']): ?>
                            <p><strong>Ghi chú:</strong> <?= esc($vac['notes']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= view('layouts/customer_footer', ['settings' => $settings ?? []]) ?>
