<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
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
                    <?= flash() ?>
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

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">
                <!-- Top Products -->
                <div class="glass-card">
                    <div class="card-header" style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                        <h3>🏆 Top Selling Products</h3>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <?php if (!empty($top_products)): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Orders</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($top_products as $product): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($product['name']) ?></td>
                                            <td><span class="badge badge-primary"><?= intval($product['order_count']) ?></span></td>
                                            <td><span class="badge badge-secondary"><?= intval($product['total_quantity']) ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted text-center">No sales data available yet.</p>
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

            <!-- Monthly Trend -->
            <?php if (!empty($monthly_trend)): ?>
                <div class="glass-card" style="margin-top: 30px;">
                    <div class="card-header" style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                        <h3>📈 Monthly Trend (Last 6 Months)</h3>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Orders</th>
                                    <th>Revenue</th>
                                    <th>Avg Order Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($monthly_trend as $trend): ?>
                                    <tr>
                                        <td><?= date('F Y', strtotime($trend['month'] . '-01')) ?></td>
                                        <td><span class="badge badge-primary"><?= $trend['order_count'] ?></span></td>
                                        <td>$<?= number_format($trend['revenue'], 2) ?></td>
                                        <td>$<?= $trend['order_count'] > 0 ? number_format($trend['revenue'] / $trend['order_count'], 2) : '0.00' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
