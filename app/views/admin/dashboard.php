<?php ob_start(); ?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <h2>🚰 Peepit Admin</h2>
        </div>
        <nav class="admin-nav">
            <ul>
                <li class="active"><a href="<?= url('admin') ?>">📊 Dashboard</a></li>
                <?php if (has_role('manager')): ?>
                    <li><a href="<?= url('admin/bottles') ?>">🚰 Bottle Models</a></li>
                    <li><a href="<?= url('admin/sizes') ?>">📏 Bottle Sizes</a></li>
                    <li><a href="<?= url('admin/colors') ?>">🎨 Color Presets</a></li>
                    <li><a href="<?= url('admin/templates') ?>">🏷️ Label Templates</a></li>
                    <li><a href="<?= url('admin/pricing') ?>">💰 Pricing</a></li>
                <?php endif; ?>
                <li><a href="<?= url('admin/orders') ?>">📦 Orders</a></li>
                <?php if (has_role('manager')): ?>
                    <li><a href="<?= url('admin/users') ?>">👥 Users</a></li>
                    <li><a href="<?= url('admin/analytics') ?>">📈 Analytics</a></li>
                    <li><a href="<?= url('admin/email-logs') ?>">✉️ Email Logs</a></li>
                <?php endif; ?>
                <?php if (has_role('superadmin')): ?>
                    <li><a href="<?= url('admin/settings') ?>">⚙️ Settings</a></li>
                <?php endif; ?>
                <?php if (user_role() === 'webmail'): ?>
                    <li><a href="<?= config('webmail_url') ?>" target="_blank">📧 Webmail</a></li>
                <?php endif; ?>
                <li><a href="<?= url() ?>">🏠 Frontend</a></li>
                <li><a href="<?= url('logout') ?>">🚪 Logout</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="admin-content">
        <div class="admin-header">
            <h1>Dashboard</h1>
            <div class="admin-user">
                <span>Welcome, <?= escape(current_user()['name']) ?></span>
                <span class="badge"><?= escape(user_role()) ?></span>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #4299e1;">👥</div>
                <div class="stat-info">
                    <h3><?= number_format($stats['total_users']) ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: #48bb78;">📦</div>
                <div class="stat-info">
                    <h3><?= number_format($stats['total_orders']) ?></h3>
                    <p>Total Orders</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: #ed8936;">⏳</div>
                <div class="stat-info">
                    <h3><?= number_format($stats['pending_orders']) ?></h3>
                    <p>Pending Orders</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: #9f7aea;">💰</div>
                <div class="stat-info">
                    <h3><?= currency_format($stats['total_revenue']) ?></h3>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>

        <div class="card mt-20">
            <div class="card-header">Recent Orders</div>
            <div class="table-responsive">
                <?php if (!empty($recent_orders)): ?>
                    <table class="admin-table">
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
                                    <td><?= currency_format($order['total_amount']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $order['status'] ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= format_date($order['created_at'], 'd M Y') ?></td>
                                    <td>
                                        <a href="<?= url('admin/orders/view/' . $order['id']) ?>" class="btn-sm">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center p-20">No orders yet</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (has_role('manager')): ?>
            <div class="grid grid-2 mt-20">
                <div class="card">
                    <div class="card-header">Quick Actions</div>
                    <div class="p-20">
                        <a href="<?= url('admin/bottles/create') ?>" class="btn btn-primary mb-10" style="display: block;">+ Add Bottle Model</a>
                        <a href="<?= url('admin/templates/create') ?>" class="btn btn-secondary mb-10" style="display: block;">+ Add Label Template</a>
                        <a href="<?= url('admin/colors/create') ?>" class="btn btn-success mb-10" style="display: block;">+ Add Color Preset</a>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">System Status</div>
                    <div class="p-20">
                        <div class="status-item">
                            <span>Database:</span>
                            <span class="badge badge-success">Connected</span>
                        </div>
                        <div class="status-item">
                            <span>PHP Version:</span>
                            <span class="badge"><?= PHP_VERSION ?></span>
                        </div>
                        <div class="status-item">
                            <span>Upload Directory:</span>
                            <span class="badge badge-<?= is_writable(__DIR__ . '/../../../public/uploads') ? 'success' : 'error' ?>">
                                <?= is_writable(__DIR__ . '/../../../public/uploads') ? 'Writable' : 'Not Writable' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<style>
    body {
        margin: 0;
        background: #f7fafc;
    }
    
    nav.navbar, footer {
        display: none;
    }
    
    .admin-layout {
        display: flex;
        min-height: 100vh;
    }
    
    .admin-sidebar {
        width: 250px;
        background: var(--dark);
        color: white;
        padding: 20px 0;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }
    
    .admin-brand {
        padding: 0 20px 20px;
        border-bottom: 1px solid #4a5568;
        margin-bottom: 20px;
    }
    
    .admin-brand h2 {
        margin: 0;
        color: var(--primary-color);
    }
    
    .admin-nav ul {
        list-style: none;
    }
    
    .admin-nav li {
        margin-bottom: 5px;
    }
    
    .admin-nav a {
        display: block;
        padding: 12px 20px;
        color: #cbd5e0;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .admin-nav li.active a,
    .admin-nav a:hover {
        background: rgba(102, 126, 234, 0.2);
        color: white;
        border-left: 3px solid var(--primary-color);
    }
    
    .admin-content {
        margin-left: 250px;
        flex: 1;
        padding: 30px;
    }
    
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .admin-header h1 {
        margin: 0;
    }
    
    .admin-user {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        background: #e2e8f0;
        color: var(--dark);
    }
    
    .badge-pending {
        background: #feebc8;
        color: #c05621;
    }
    
    .badge-processing {
        background: #bee3f8;
        color: #2c5282;
    }
    
    .badge-completed {
        background: #c6f6d5;
        color: #2f855a;
    }
    
    .badge-cancelled {
        background: #fed7d7;
        color: #c53030;
    }
    
    .badge-success {
        background: #c6f6d5;
        color: #2f855a;
    }
    
    .badge-error {
        background: #fed7d7;
        color: #c53030;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }
    
    .stat-info h3 {
        margin: 0 0 5px 0;
        font-size: 28px;
        color: var(--dark);
    }
    
    .stat-info p {
        margin: 0;
        color: #718096;
    }
    
    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .admin-table th,
    .admin-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }
    
    .admin-table th {
        background: #f7fafc;
        font-weight: 600;
        color: var(--dark);
    }
    
    .admin-table tr:hover {
        background: #f7fafc;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 14px;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .status-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
    }
    
    .status-item:last-child {
        border-bottom: none;
    }
    
    .mb-10 {
        margin-bottom: 10px;
    }
</style>

<?php
$content = ob_get_clean();
$title = 'Dashboard - Admin Panel - Peepit';
include __DIR__ . '/../layouts/frontend.php';
?>
