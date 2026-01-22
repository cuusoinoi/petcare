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
            <?php
            // Helper function for formatting numbers
            function formatNumberShort($num) {
                if ($num >= 1000000000) {
                    return round($num / 1000000000, 1) . 'B';
                } elseif ($num >= 1000000) {
                    return round($num / 1000000, 1) . 'M';
                } elseif ($num >= 1000) {
                    return round($num / 1000, 1) . 'K';
                } else {
                    return $num;
                }
            }
            ?>
            
            <div class="dashboard__cards">
                <div class="card customer">
                    <i class="card__icon fa-solid fa-user"></i>
                    <div class="card__info">
                        <span class="card__value"><?= formatNumberShort($customerCount) ?></span>
                    </div>
                    <p class="card__title">Tổng khách hàng</p>
                </div>

                <div class="card pet">
                    <i class="card__icon fa-solid fa-paw"></i>
                    <div class="card__info">
                        <span class="card__value"><?= formatNumberShort($petCount) ?></span>
                    </div>
                    <p class="card__title">Tổng thú cưng</p>
                </div>

                <div class="card medical">
                    <i class="card__icon fa-solid fa-stethoscope"></i>
                    <div class="card__info">
                        <span class="card__value"><?= formatNumberShort($medicalRecordCount) ?></span>
                        <span class="card__percent <?= $medicalPercentChange >= 0 ? 'up' : 'down' ?>">
                            <i class="fa <?= $medicalPercentChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' ?>"></i>
                            <?= number_format($medicalPercentChange, 1) . '%' ?>
                        </span>
                    </div>
                    <p class="card__title">Lượt khám</p>
                </div>

                <div class="card enclosure">
                    <i class="card__icon fa-solid fa-house"></i>
                    <div class="card__info">
                        <span class="card__value"><?= formatNumberShort($petEnclosureCount) ?></span>
                        <span class="card__percent <?= $enclosurePercentChange >= 0 ? 'up' : 'down' ?>">
                            <i class="fa <?= $enclosurePercentChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' ?>"></i>
                            <?= number_format($enclosurePercentChange, 1) . '%' ?>
                        </span>
                    </div>
                    <p class="card__title">Lượt lưu chuồng</p>
                </div>

                <div class="card revenue">
                    <i class="card__icon fa-solid fa-dollar-sign"></i>
                    <div class="card__info">
                        <span class="card__value"><?= formatNumberShort($invoiceRevenue) ?></span>
                        <span class="card__percent <?= $revenuePercentChange >= 0 ? 'up' : 'down' ?>">
                            <i class="fa <?= $revenuePercentChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' ?>"></i>
                            <?= number_format($revenuePercentChange, 1) . '%' ?>
                        </span>
                    </div>
                    <p class="card__title">Doanh thu</p>
                </div>
            </div>

            <!-- ===== Biểu đồ ===== -->
            <div class="dashboard__charts">
                <div class="chart-card">
                    <h3 class="chart-title">Lượt khám (7 ngày gần nhất)</h3>
                    <canvas id="medicalChart" height="200"></canvas>
                </div>

                <div class="chart-card">
                    <h3 class="chart-title">Check-in / Check-out (7 ngày gần nhất)</h3>
                    <canvas id="checkinCheckoutChart" height="200"></canvas>
                </div>
            </div>

            <div class="dashboard__charts">
                <div class="chart-card">
                    <h3 class="chart-title">Doanh thu theo tháng (12 tháng)</h3>
                    <canvas id="revenueChart" height="300"></canvas>
                </div>

                <div class="chart-card">
                    <h3 class="chart-title">Tỷ trọng doanh thu theo loại dịch vụ</h3>
                    <canvas id="serviceRevenueChart" height="300"></canvas>
                </div>
            </div>
        </main>
        
        <?= view('layouts/admin_footer') ?>
    </main>

    <script src="<?= base_url('assets/js/script.js') ?>" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ===== LƯỢT KHÁM (7 NGÀY GẦN NHẤT) =====
        const ctxMedical = document.getElementById('medicalChart').getContext('2d');
        const medicalGradient = ctxMedical.createLinearGradient(0, 0, 0, 300);
        medicalGradient.addColorStop(0, 'rgba(220, 53, 69, 0.6)');
        medicalGradient.addColorStop(1, 'rgba(220, 53, 69, 0)');

        const medicalData = <?= json_encode($counts) ?>;
        const medicalLabels = <?= json_encode($dates) ?>;

        new Chart(ctxMedical, {
            type: 'line',
            data: {
                labels: medicalLabels,
                datasets: [{
                    label: 'Lượt khám',
                    data: medicalData,
                    backgroundColor: medicalGradient,
                    borderColor: '#dc3545',
                    borderWidth: 1.5,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#dc3545'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    }
                }
            }
        });

        // ===== CHECK-IN / CHECK-OUT =====
        const checkinCheckoutData = <?= json_encode($checkinCheckoutData) ?>;
        const dates2 = Object.keys(checkinCheckoutData);
        const checkin = Object.values(checkinCheckoutData).map(d => d.checkin);
        const checkout = Object.values(checkinCheckoutData).map(d => d.checkout);

        const ctxCheck = document.getElementById('checkinCheckoutChart').getContext('2d');
        const checkinGradient = ctxCheck.createLinearGradient(0, 0, 0, 400);
        checkinGradient.addColorStop(0, '#ffcd39');
        checkinGradient.addColorStop(1, '#fff3cd');
        const checkoutGradient = ctxCheck.createLinearGradient(0, 0, 0, 400);
        checkoutGradient.addColorStop(0, '#28a745');
        checkoutGradient.addColorStop(1, '#c8f7d2');

        new Chart(ctxCheck, {
            type: 'bar',
            data: {
                labels: dates2,
                datasets: [{
                    label: 'Check-in',
                    data: checkin,
                    backgroundColor: checkinGradient,
                    borderColor: '#ffc107',
                    borderWidth: 1
                }, {
                    label: 'Check-out',
                    data: checkout,
                    backgroundColor: checkoutGradient,
                    borderColor: '#28a745',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: { stacked: true },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });

        // ===== DOANH THU THEO THÁNG =====
        const monthlyRevenueData = <?= json_encode(array_values($monthlyRevenueStats)) ?>;
        const monthlyLabels = <?= json_encode(array_map(function($m) { return date('m/Y', strtotime($m . '-01')); }, array_keys($monthlyRevenueStats))) ?>;

        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        const revenueGradient = ctxRevenue.createLinearGradient(0, 0, 0, 300);
        revenueGradient.addColorStop(0, 'rgba(17, 122, 139, 0.5)');
        revenueGradient.addColorStop(1, 'rgba(78, 115, 223, 0)');

        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: monthlyRevenueData,
                    borderColor: '#17a2b8',
                    backgroundColor: revenueGradient,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => value.toLocaleString('vi-VN') + ' ₫'
                        }
                    }
                }
            }
        });

        // ===== DOANH THU THEO DỊCH VỤ =====
        const ctxService = document.getElementById('serviceRevenueChart').getContext('2d');
        const baseColors = [
            ['#4e73df', '#a5b6f2'], ['#1cc88a', '#a0f0c0'], ['#36b9cc', '#a5e7ef'],
            ['#f6c23e', '#fde4a5'], ['#e74a3b', '#f4a9a2'], ['#8e44ad', '#d7bce7'],
            ['#fd7e14', '#ffd6a5'], ['#20c997', '#9ff2d0'], ['#6610f2', '#b28df7'],
            ['#6f42c1', '#cbb2f5'], ['#adb5bd', '#dee2e6'], ['#795548', '#d7ccc8']
        ];

        const colors = <?= json_encode($serviceNames) ?>.map((_, i) => {
            const grad = ctxService.createLinearGradient(0, 0, 300, 300);
            grad.addColorStop(0, baseColors[i % baseColors.length][0]);
            grad.addColorStop(1, baseColors[i % baseColors.length][1]);
            return grad;
        });

        new Chart(ctxService, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($serviceNames) ?>,
                datasets: [{
                    data: <?= json_encode($serviceRevenues) ?>,
                    backgroundColor: colors,
                    borderWidth: 1,
                    borderColor: '#fff',
                    hoverOffset: 12
                }]
            },
            options: {
                responsive: true,
                aspectRatio: 1.75,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed.toLocaleString('vi-VN') + ' ₫';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
