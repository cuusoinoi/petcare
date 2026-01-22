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
</head>
<body>
    <div class="overlay" id="overlay"></div>
    <?= view('layouts/admin_sidebar') ?>
    <main class="main">
        <?= view('layouts/admin_header') ?>
        <main class="content">
            <div class="form-container form-container-wide">
                <h2>Chỉnh sửa hóa đơn #<?= $invoice['invoice_id'] ?></h2>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <form action="<?= site_url('admin/invoices/update/' . $invoice['invoice_id']) ?>" method="post" id="invoiceForm">
                    <?= csrf_field() ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="customer_id">Khách hàng <span class="required-field">*</span></label>
                            <select id="customer_id" name="customer_id" required>
                                <option value="">-- Chọn khách hàng --</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer['customer_id'] ?>" <?= $customer['customer_id'] == $invoice['customer_id'] ? 'selected' : '' ?>><?= esc($customer['customer_name']) ?> - <?= esc($customer['customer_phone_number']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pet_id">Thú cưng <span class="required-field">*</span></label>
                            <select id="pet_id" name="pet_id" required>
                                <option value="">-- Chọn thú cưng --</option>
                                <?php foreach ($pets as $pet): ?>
                                    <option value="<?= $pet['pet_id'] ?>" data-customer="<?= $pet['customer_id'] ?>" <?= $pet['pet_id'] == $invoice['pet_id'] ? 'selected' : '' ?>><?= esc($pet['pet_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="invoice_date">Ngày hóa đơn</label>
                            <input type="date" id="invoice_date" name="invoice_date" value="<?= date('Y-m-d', strtotime($invoice['invoice_date'])) ?>">
                        </div>
                    </div>

                    <h3>Chi tiết dịch vụ</h3>
                    <table class="admin-data-table" id="serviceTable">
                        <thead>
                            <tr>
                                <th>Dịch vụ</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="serviceRows">
                            <?php if (!empty($invoiceDetails)): ?>
                                <?php foreach ($invoiceDetails as $detail): ?>
                                    <tr class="service-row">
                                        <td>
                                            <select name="service_ids[]" class="service-select" onchange="updatePrice(this)">
                                                <option value="">-- Chọn dịch vụ --</option>
                                                <?php foreach ($serviceTypes as $service): ?>
                                                    <option value="<?= $service['service_type_id'] ?>" data-price="<?= esc($service['price'] ?? 0) ?>" <?= $service['service_type_id'] == $detail['service_type_id'] ? 'selected' : '' ?>><?= esc($service['service_name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="number" name="quantities[]" class="quantity-input" value="<?= $detail['quantity'] ?>" min="1" onchange="calculateRow(this)"></td>
                                        <td><input type="number" name="unit_prices[]" class="unit-price-input" value="<?= $detail['unit_price'] ?>" readonly></td>
                                        <td><input type="number" name="total_prices[]" class="total-price-input" value="<?= $detail['total_price'] ?>" readonly></td>
                                        <td><button type="button" class="btn btn-icon btn-delete" onclick="removeRow(this)"><i class="fas fa-times"></i></button></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="service-row">
                                    <td>
                                        <select name="service_ids[]" class="service-select" onchange="updatePrice(this)">
                                            <option value="">-- Chọn dịch vụ --</option>
                                            <?php foreach ($serviceTypes as $service): ?>
                                                <option value="<?= $service['service_type_id'] ?>" data-price="<?= esc($service['price'] ?? 0) ?>"><?= esc($service['service_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input type="number" name="quantities[]" class="quantity-input" value="1" min="1" onchange="calculateRow(this)"></td>
                                    <td><input type="number" name="unit_prices[]" class="unit-price-input" value="0" readonly></td>
                                    <td><input type="number" name="total_prices[]" class="total-price-input" value="0" readonly></td>
                                    <td><button type="button" class="btn btn-icon btn-delete" onclick="removeRow(this)"><i class="fas fa-times"></i></button></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-add" onclick="addServiceRow()"><i class="fas fa-plus"></i> Thêm dịch vụ</button>

                    <div class="form-row mt-20">
                        <div class="form-group">
                            <label for="discount">Giảm giá (VNĐ)</label>
                            <input type="number" id="discount" name="discount" value="<?= $invoice['discount'] ?>" onchange="calculateTotal()">
                        </div>
                        <div class="form-group">
                            <label for="deposit">Đặt cọc (VNĐ)</label>
                            <input type="number" id="deposit" name="deposit" value="<?= $invoice['deposit'] ?>" onchange="calculateTotal()">
                        </div>
                    </div>

                    <div class="invoice-summary">
                        <p><strong>Tổng tiền dịch vụ:</strong> <span id="subtotalDisplay"><?= number_format($invoice['subtotal'], 0, ',', '.') ?></span> đ</p>
                        <p><strong>Giảm giá:</strong> <span id="discountDisplay"><?= number_format($invoice['discount'], 0, ',', '.') ?></span> đ</p>
                        <p><strong>Đặt cọc:</strong> <span id="depositDisplay"><?= number_format($invoice['deposit'], 0, ',', '.') ?></span> đ</p>
                        <p class="total"><strong>Còn phải thanh toán:</strong> <span id="totalDisplay"><?= number_format($invoice['total_amount'], 0, ',', '.') ?></span> đ</p>
                    </div>

                    <input type="hidden" name="subtotal" id="subtotal" value="<?= $invoice['subtotal'] ?>">
                    <input type="hidden" name="total_amount" id="total_amount" value="<?= $invoice['total_amount'] ?>">

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu hóa đơn</button>
                        <a href="<?= site_url('admin/invoices') ?>" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        <?= view('layouts/admin_footer') ?>
    </main>
    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
    <script>
        // Tự động điền giá khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.service-select').forEach(function(select) {
                if (select.value) {
                    updatePrice(select);
                }
            });
        });

        function updatePrice(select) {
            const row = select.closest('tr');
            const option = select.options[select.selectedIndex];
            const price = parseFloat(option.dataset.price) || 0;
            row.querySelector('.unit-price-input').value = price;
            calculateRow(select);
        }

        function calculateRow(input) {
            const row = input.closest('tr');
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const unitPrice = parseFloat(row.querySelector('.unit-price-input').value) || 0;
            const totalPrice = quantity * unitPrice;
            row.querySelector('.total-price-input').value = totalPrice;
            calculateTotal();
        }

        function calculateTotal() {
            let subtotal = 0;
            document.querySelectorAll('.total-price-input').forEach(input => {
                subtotal += parseFloat(input.value) || 0;
            });
            
            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const deposit = parseFloat(document.getElementById('deposit').value) || 0;
            const total = subtotal - discount - deposit;

            document.getElementById('subtotal').value = subtotal;
            document.getElementById('total_amount').value = total;
            document.getElementById('subtotalDisplay').innerText = subtotal.toLocaleString('vi-VN');
            document.getElementById('discountDisplay').innerText = discount.toLocaleString('vi-VN');
            document.getElementById('depositDisplay').innerText = deposit.toLocaleString('vi-VN');
            document.getElementById('totalDisplay').innerText = total.toLocaleString('vi-VN');
        }

        function addServiceRow() {
            const tbody = document.getElementById('serviceRows');
            const firstRow = tbody.querySelector('.service-row');
            const newRow = firstRow.cloneNode(true);
            newRow.querySelector('.service-select').selectedIndex = 0;
            newRow.querySelector('.quantity-input').value = 1;
            newRow.querySelector('.unit-price-input').value = 0;
            newRow.querySelector('.total-price-input').value = 0;
            tbody.appendChild(newRow);
        }

        function removeRow(button) {
            const tbody = document.getElementById('serviceRows');
            if (tbody.querySelectorAll('.service-row').length > 1) {
                button.closest('tr').remove();
                calculateTotal();
            }
        }
    </script>
</body>
</html>
