<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    <?php require_once __DIR__ . '/../../layouts/admin.php'; ?>

    <div class="admin-content">
        <div class="container">
            <div class="page-header">
                <h1>📊 Analytics & Reports</h1>
                <p class="text-muted">Business performance overview and insights</p>
            </div>

            <?php if (has_flash()): ?>
                <div class="alert alert-<?= flash_type() ?>">
                    <?= get_flash() ?>
                </div>
            <?php endif; ?>

            <!-- Key Metrics -->
            <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div class="stat-card glass-card" style="padding: 20px;">
                    <div class="stat-icon" style="font-size: 2rem; margin-bottom: 10px;">📦</div>
                    <div class="stat-value" style="font-size: 2rem; font-weight: bold; color: #0EA5E9;">
                        <?= $stats['total_orders'] ?? 0 ?>
                    </div>
                    <div class="stat-label" style="color: #64748b;">Total Orders</div>
                    <div class="stat-detail" style="font-size: 0.875rem; color: #94a3b8; margin-top: 5px;">
                        <?= $stats['pending_orders'] ?? 0 ?> pending
                    </div>
                </div>

                <div class="stat-card glass-card" style="padding: 20px;">
                    <div class="stat-icon" style="font-size: 2rem; margin-bottom: 10px;">💰</div>
                    <div class="stat-value" style="font-size: 2rem; font-weight: bold; color: #10B981;">
                        $<?= number_format($stats['total_revenue'] ?? 0, 2) ?>
                    </div>
                    <div class="stat-label" style="color: #64748b;">Total Revenue</div>
                    <div class="stat-detail" style="font-size: 0.875rem; color: #94a3b8; margin-top: 5px;">
                        $<?= number_format($stats['monthly_revenue'] ?? 0, 2) ?> this month
                    </div>
                </div>

                <div class="stat-card glass-card" style="padding: 20px;">
                    <div class="stat-icon" style="font-size: 2rem; margin-bottom: 10px;">✅</div>
                    <div class="stat-value" style="font-size: 2rem; font-weight: bold; color: #8B5CF6;">
                        <?= $stats['completed_orders'] ?? 0 ?>
                    </div>
                    <div class="stat-label" style="color: #64748b;">Completed Orders</div>
                    <div class="stat-detail" style="font-size: 0.875rem; color: #94a3b8; margin-top: 5px;">
                        <?= $stats['total_orders'] > 0 ? round(($stats['completed_orders'] / $stats['total_orders']) * 100) : 0 ?>% completion rate
                    </div>
                </div>

                <div class="stat-card glass-card" style="padding: 20px;">
                    <div class="stat-icon" style="font-size: 2rem; margin-bottom: 10px;">👥</div>
                    <div class="stat-value" style="font-size: 2rem; font-weight: bold; color: #F59E0B;">
                        <?= $stats['total_customers'] ?? 0 ?>
                    </div>
                    <div class="stat-label" style="color: #64748b;">Total Customers</div>
                    <div class="stat-detail" style="font-size: 0.875rem; color: #94a3b8; margin-top: 5px;">
                        +<?= $stats['new_customers'] ?? 0 ?> this month
                    </div>
                </div>
            </div>

            <!-- Export Button -->
            <div style="margin-bottom: 30px; text-align: right;">
                <a href="<?= url('admin/analytics/export?format=csv') ?>" class="btn btn-primary">
                    📥 Export to CSV
                </a>
                <a href="<?= url('admin/analytics/export?format=pdf') ?>" class="btn btn-secondary" style="margin-left: 10px;">
                    📄 Export to PDF
                </a>
            </div>

            <!-- Interactive Charts Row -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 30px; margin-bottom: 30px;">
                <!-- Revenue Trend Chart -->
                <div class="glass-card">
                    <div class="card-header" style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                        <h3>💰 Revenue Trend</h3>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <canvas id="revenueTrendChart" height="300"></canvas>
                    </div>
                </div>

                <!-- Order Volume Chart -->
                <div class="glass-card">
                    <div class="card-header" style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                        <h3>📦 Order Volume</h3>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <canvas id="orderVolumeChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Products Chart -->
            <div class="glass-card" style="margin-bottom: 30px;">
                <div class="card-header" style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                    <h3>🏆 Top Selling Products</h3>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <canvas id="topProductsChart" height="100"></canvas>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">
                <!-- Customer Analytics -->
                <div class="glass-card">
                    <div class="card-header" style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                        <h3>👥 Top Customers</h3>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <?php if (!empty($top_customers)): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Orders</th>
                                        <th>Total Spent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($top_customers as $customer): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($customer['name']) ?></td>
                                            <td><span class="badge badge-primary"><?= intval($customer['order_count']) ?></span></td>
                                            <td>$<?= number_format($customer['total_spent'] ?? 0, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted text-center">No customer data available yet.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="glass-card">
                    <div class="card-header" style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                        <h3>🕐 Recent Orders</h3>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <?php if (!empty($recent_orders)): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td><a href="<?= url("admin/orders/" . intval($order['id'])) ?>">#<?= intval($order['id']) ?></a></td>
                                            <td><?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></td>
                                            <td>
                                                <?php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'processing' => 'info',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusColor = $statusColors[$order['status']] ?? 'secondary';
                                                ?>
                                                <span class="badge badge-<?= $statusColor ?>"><?= ucfirst($order['status']) ?></span>
                                            </td>
                                            <td>$<?= number_format($order['total_amount'] ?? 0, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted text-center">No recent orders.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        // Revenue Trend Chart
        <?php if (!empty($monthly_trend)): ?>
        const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
        new Chart(revenueTrendCtx, {
            type: 'line',
            data: {
                labels: [<?php echo implode(',', array_map(function($t) { return '"' . date('M Y', strtotime($t['month'] . '-01')) . '"'; }, $monthly_trend)); ?>],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [<?php echo implode(',', array_column($monthly_trend, 'revenue')); ?>],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Order Volume Chart
        const orderVolumeCtx = document.getElementById('orderVolumeChart').getContext('2d');
        new Chart(orderVolumeCtx, {
            type: 'bar',
            data: {
                labels: [<?php echo implode(',', array_map(function($t) { return '"' . date('M Y', strtotime($t['month'] . '-01')) . '"'; }, $monthly_trend)); ?>],
                datasets: [{
                    label: 'Orders',
                    data: [<?php echo implode(',', array_column($monthly_trend, 'order_count')); ?>],
                    backgroundColor: '#0EA5E9',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        <?php endif; ?>

        // Top Products Chart
        <?php if (!empty($top_products)): ?>
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: [<?php echo implode(',', array_map(function($p) { return '"' . htmlspecialchars($p['name']) . '"'; }, $top_products)); ?>],
                datasets: [{
                    label: 'Orders',
                    data: [<?php echo implode(',', array_map(function($p) { return intval($p['order_count']); }, $top_products)); ?>],
                    backgroundColor: [
                        '#0EA5E9',
                        '#10B981',
                        '#F59E0B',
                        '#8B5CF6',
                        '#EF4444'
                    ],
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        <?php endif; ?>
    </script>
        </div>
    </div>
</body>
</html>
