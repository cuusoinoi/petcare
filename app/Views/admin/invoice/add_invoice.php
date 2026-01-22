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
                <h2>Tạo hóa đơn mới</h2>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>
                <form action="<?= site_url('admin/invoices/store') ?>" method="post" id="invoiceForm">
                    <?= csrf_field() ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="customer_id">Khách hàng <span class="required-field">*</span></label>
                            <select id="customer_id" name="customer_id" required>
                                <option value="">-- Chọn khách hàng --</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer['customer_id'] ?>"><?= esc($customer['customer_name']) ?> - <?= esc($customer['customer_phone_number']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pet_id">Thú cưng <span class="required-field">*</span></label>
                            <select id="pet_id" name="pet_id" required>
                                <option value="">-- Chọn thú cưng --</option>
                                <?php foreach ($pets as $pet): ?>
                                    <option value="<?= $pet['pet_id'] ?>" data-customer="<?= $pet['customer_id'] ?>"><?= esc($pet['pet_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="invoice_date">Ngày hóa đơn</label>
                            <input type="date" id="invoice_date" name="invoice_date" value="<?= date('Y-m-d') ?>">
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
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-add" onclick="addServiceRow()"><i class="fas fa-plus"></i> Thêm dịch vụ</button>

                    <div class="form-row mt-20">
                        <div class="form-group">
                            <label for="discount">Giảm giá (VNĐ)</label>
                            <input type="number" id="discount" name="discount" value="0" onchange="calculateTotal()">
                        </div>
                        <div class="form-group">
                            <label for="deposit">Đặt cọc (VNĐ)</label>
                            <input type="number" id="deposit" name="deposit" value="0" onchange="calculateTotal()">
                        </div>
                    </div>

                    <div class="invoice-summary">
                        <p><strong>Tổng tiền dịch vụ:</strong> <span id="subtotalDisplay">0</span> đ</p>
                        <p><strong>Giảm giá:</strong> <span id="discountDisplay">0</span> đ</p>
                        <p><strong>Đặt cọc:</strong> <span id="depositDisplay">0</span> đ</p>
                        <p class="total"><strong>Còn phải thanh toán:</strong> <span id="totalDisplay">0</span> đ</p>
                    </div>

                    <input type="hidden" name="subtotal" id="subtotal" value="0">
                    <input type="hidden" name="total_amount" id="total_amount" value="0">

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
        // Filter pets by customer
        document.getElementById('customer_id').addEventListener('change', function() {
            const customerId = this.value;
            const petSelect = document.getElementById('pet_id');
            const options = petSelect.querySelectorAll('option');
            
            options.forEach(option => {
                if (option.value === '' || option.dataset.customer === customerId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
            petSelect.value = '';
        });

        function updatePrice(select) {
            const row = select.closest('tr');
            const option = select.options[select.selectedIndex];
            const price = option.dataset.price || 0;
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
