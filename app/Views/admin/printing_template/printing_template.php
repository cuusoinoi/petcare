<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('admin_assets/images/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/grid.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/responsive.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        @media print {
            .sidebar, .header, .no-print, .filter-box, .actions { display: none !important; }
            .main { margin: 0; padding: 0; }
            .content { padding: 0; }
            .preview-box { border: none; box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="overlay" id="overlay"></div>
    <?= view('layouts/admin_sidebar') ?>
    <main class="main">
        <?= view('layouts/admin_header') ?>
        <main class="content">
            <h1 class="no-print">Mẫu in hóa đơn</h1>

            <!-- Dropdown chọn hóa đơn -->
            <div class="filter-box no-print">
                <select id="printInvoiceSelect">
                    <?php foreach ($invoices as $inv): ?>
                        <option value="<?= $inv['invoice_id'] ?>" <?= $mappedInvoiceId == $inv['invoice_id'] ? 'selected' : '' ?>>
                            <?= esc($inv['pet_name']) ?> (<?= esc($inv['customer_name']) ?>) - <?= date("d/m/Y H:i", strtotime($inv['invoice_date'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nhóm nút -->
            <div class="actions action-buttons no-print">
                <button class="btn-action btn-commit" id="btnRenderCommit" title="Xem Giấy cam kết"><i class="fas fa-file-contract"></i> Xem Giấy cam kết</button>
                <button class="btn-action btn-invoice" id="btnRenderInvoice" title="Xem Hóa đơn"><i class="fas fa-file-invoice"></i> Xem Hóa đơn</button>
                <button class="btn-action btn-print" id="btnPrintNow" title="In Trang này"><i class="fas fa-print"></i> In Trang này</button>
            </div>

            <!-- Vùng xem trước -->
            <div id="printArea" class="preview-box" style="display:none; margin-top: 20px; padding: 20px; border: 1px solid #ddd; background: #fff;">
                <!-- Nội dung sẽ được render ở JS -->
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>

    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
    <script>
        const printArea = document.getElementById('printArea');

        document.getElementById('btnRenderCommit').addEventListener('click', function() {
            let invoiceId = document.getElementById('printInvoiceSelect').value;
            if (!invoiceId) {
                showAlert("Vui lòng chọn hóa đơn!", "Thông báo", "warning");
                return;
            }

            fetch("<?= site_url('admin/printing-template/load-commit/') ?>" + invoiceId)
                .then(res => res.text())
                .then(html => {
                    printArea.style.display = 'block';
                    printArea.innerHTML = html;
                    printArea.scrollIntoView({ behavior: 'smooth' });
                });
        });

        document.getElementById('btnRenderInvoice').addEventListener('click', function() {
            let invoiceId = document.getElementById('printInvoiceSelect').value;
            if (!invoiceId) {
                showAlert("Vui lòng chọn hóa đơn!", "Thông báo", "warning");
                return;
            }

            fetch("<?= site_url('admin/printing-template/load-invoice/') ?>" + invoiceId)
                .then(res => res.text())
                .then(html => {
                    printArea.style.display = 'block';
                    printArea.innerHTML = html;
                    printArea.scrollIntoView({ behavior: 'smooth' });
                });
        });

        document.getElementById('btnPrintNow').addEventListener('click', function() {
            var printContent = document.getElementById('printArea').innerHTML;
            if (!printContent.trim()) {
                showAlert("Chưa có nội dung để in!", "Thông báo", "warning");
                return;
            }
            window.print();
        });

        // Auto-load on page load if has mapped invoice
        document.addEventListener('DOMContentLoaded', function() {
            const mappedInvoiceId = <?= json_encode($mappedInvoiceId) ?>;
            const petEnclosureId = <?= json_encode($petEnclosureId) ?>;
            const invoiceId = <?= json_encode($invoiceId) ?>;

            if (mappedInvoiceId) {
                let endpoint = "";
                if (petEnclosureId > 0) {
                    endpoint = "<?= site_url('admin/printing-template/load-commit/') ?>" + mappedInvoiceId;
                } else if (invoiceId > 0) {
                    endpoint = "<?= site_url('admin/printing-template/load-invoice/') ?>" + mappedInvoiceId;
                }

                if (endpoint) {
                    fetch(endpoint)
                        .then(res => res.text())
                        .then(html => {
                            printArea.style.display = 'block';
                            printArea.innerHTML = html;
                            printArea.scrollIntoView({ behavior: 'smooth' });
                        });
                }
            }
        });
    </script>
</body>
</html>
