<?php
use App\Models\GeneralSettingModel;

$settingModel = new GeneralSettingModel();
$settings = $settingModel->getSettings();
?>
<footer class="footer">
    <p>&copy; <?= date('Y') ?> <?= esc($settings['clinic_name'] ?? 'Phòng khám thú y') ?>. All Rights Reserved.</p>
</footer>
