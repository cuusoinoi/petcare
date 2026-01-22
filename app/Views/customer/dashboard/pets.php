<?= view('layouts/customer_header', ['settings' => $settings ?? []]) ?>

<section class="section">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; flex-wrap: wrap; gap: 1rem;">
            <h2 class="section-title" style="margin: 0;">Thú cưng của tôi</h2>
            <a href="<?= site_url('customer/dashboard/pets/add') ?>" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 0.8rem 1.5rem; white-space: nowrap; flex-shrink: 0; width: auto !important;">
                <i class="fas fa-plus"></i> Thêm thú cưng
            </a>
        </div>

        <?php if (empty($pets)): ?>
            <div class="service-card" style="text-align: center; padding: 4rem;">
                <i class="fas fa-paw" style="font-size: 5rem; color: #ddd; margin-bottom: 2rem;"></i>
                <p style="font-size: 1.6rem; color: #666; margin-bottom: 2rem;">Bạn chưa có thú cưng nào</p>
                <a href="<?= site_url('customer/dashboard/pets/add') ?>" class="btn-submit" style="text-decoration: none; display: inline-block;">
                    Thêm thú cưng đầu tiên
                </a>
            </div>
        <?php else: ?>
            <div class="services-grid">
                <?php foreach ($pets as $pet): ?>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-paw"></i>
                        </div>
                        <h3><?= esc($pet['pet_name']) ?></h3>
                        <p><strong>Loài:</strong> <?= esc($pet['pet_species'] ?? 'Chưa cập nhật') ?></p>
                        <p><strong>Giới tính:</strong> <?= $pet['pet_gender'] == '0' ? 'Đực' : ($pet['pet_gender'] == '1' ? 'Cái' : 'Chưa cập nhật') ?></p>
                        <?php if ($pet['pet_dob']): ?>
                            <p><strong>Ngày sinh:</strong> <?= date('d/m/Y', strtotime($pet['pet_dob'])) ?></p>
                        <?php endif; ?>
                        <?php if ($pet['pet_weight']): ?>
                            <p><strong>Cân nặng:</strong> <?= $pet['pet_weight'] ?> kg</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= view('layouts/customer_footer', ['settings' => $settings ?? []]) ?>
