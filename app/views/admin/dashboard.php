<?php ob_start(); ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Total Users</div>
            <div class="stat-icon"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-value"><?= number_format($stats['total_users']) ?></div>
        <div class="stat-trend"><i class="fas fa-arrow-up"></i> Active accounts</div>
    </div>
    
    <div class="stat-card success">
        <div class="stat-header">
            <div class="stat-title">Total Orders</div>
            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
        </div>
        <div class="stat-value"><?= number_format($stats['total_orders']) ?></div>
        <div class="stat-trend"><i class="fas fa-chart-line"></i> All time</div>
    </div>
    
    <div class="stat-card warning">
        <div class="stat-header">
            <div class="stat-title">Pending Orders</div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-value"><?= number_format($stats['pending_orders']) ?></div>
        <div class="stat-trend"><i class="fas fa-exclamation-circle"></i> Needs attention</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Total Revenue</div>
            <div class="stat-icon"><i class="fas fa-rupee-sign"></i></div>
        </div>
        <div class="stat-value">₹<?= number_format($stats['total_revenue'], 2) ?></div>
        <div class="stat-trend"><i class="fas fa-chart-line"></i> Lifetime</div>
    </div>
</div>

<div class="card admin-table">
    <h2 style="padding: 20px 20px 0; margin: 0;">Recent Orders</h2>
    <?php if (!empty($recent_orders)): ?>
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_orders as $order): ?>
                    <tr>
                        <td><strong><?= escape($order['order_number']) ?></strong></td>
                        <td><?= escape($order['user_name']) ?></td>
                        <td>₹<?= number_format($order['total_amount'], 2) ?></td>
                        <td>
                            <span class="status-badge status-<?= $order['status'] ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                        <td>
                            <a href="<?= url('admin/orders/view/' . $order['id']) ?>" class="btn btn-primary" style="padding: 8px 15px; font-size: 14px;">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center" style="padding: 40px;">No orders yet</p>
    <?php endif; ?>
</div>

<?php if (has_role('manager')): ?>
    <div class="grid grid-2" style="margin-top: 30px;">
        <div class="card">
            <h2 style="margin: 0 0 20px;">Quick Actions</h2>
            <div style="display: grid; gap: 10px;">
                <a href="<?= url('admin/bottles/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Add Bottle Model
                </a>
                <a href="<?= url('admin/templates/create') ?>" class="btn btn-secondary">
                    <i class="fas fa-plus-circle"></i> Add Label Template
                </a>
                <a href="<?= url('admin/colors/create') ?>" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Add Color Preset
                </a>
            </div>
        </div>
        
        <div class="card">
            <h2 style="margin: 0 0 20px;">System Status</h2>
            <?php if (has_role('superadmin')): ?>
                <div style="display: grid; gap: 15px;">
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                        <span>Database:</span>
                        <span class="status-badge status-completed">Connected</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                        <span>PHP Version:</span>
                        <span class="badge"><?= PHP_VERSION ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                        <span>Upload Directory:</span>
                        <span class="status-badge status-<?= is_writable(__DIR__ . '/../../../public/uploads') ? 'completed' : 'cancelled' ?>">
                            <?= is_writable(__DIR__ . '/../../../public/uploads') ? 'Writable' : 'Not Writable' ?>
                        </span>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-center">System status: ✅ Operational</p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$page_title = 'Dashboard';
$current_page = 'dashboard';
include __DIR__ . '/../layouts/admin.php';
?>
