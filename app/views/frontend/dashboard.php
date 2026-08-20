<?php ob_start(); ?>

<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="header-content">
                <div class="welcome-section">
                    <h1><i class="fas fa-tachometer-alt"></i> My Dashboard</h1>
                    <p>Welcome back, <strong><?= escape($user['name']) ?></strong>!</p>
                </div>
                <div class="quick-actions">
                    <a href="<?= url('order/start') ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> New Order
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card glass-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['total_orders']) ?></h3>
                        <p>Total Orders</p>
                    </div>
                </div>

                <div class="stat-card glass-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['pending_orders']) ?></h3>
                        <p>Pending Orders</p>
                    </div>
                </div>

                <div class="stat-card glass-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['completed_orders']) ?></h3>
                        <p>Completed Orders</p>
                    </div>
                </div>

                <div class="stat-card glass-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>₹<?= number_format($stats['total_spent'], 2) ?></h3>
                        <p>Total Spent</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="dashboard-content">
        <div class="container">
            <div class="content-grid">
                <!-- Recent Orders -->
                <div class="content-section">
                    <div class="section-header">
                        <h2><i class="fas fa-list-alt"></i> Recent Orders</h2>
                        <a href="<?= url('my-orders') ?>" class="view-all-link">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <?php if (!empty($recent_orders)): ?>
                        <div class="orders-list">
                            <?php foreach ($recent_orders as $order): ?>
                                <div class="order-card glass-card">
                                    <div class="order-header">
                                        <div class="order-info">
                                            <h3>Order #<?= escape($order['order_number']) ?></h3>
                                            <span class="order-date">
                                                <i class="fas fa-calendar"></i>
                                                <?= date('M d, Y', strtotime($order['created_at'])) ?>
                                            </span>
                                        </div>
                                        <div class="order-status">
                                            <?php
                                            $statusColors = [
                                                'pending' => '#f59e0b',
                                                'processing' => '#3b82f6',
                                                'shipped' => '#8b5cf6',
                                                'completed' => '#10b981',
                                                'cancelled' => '#ef4444'
                                            ];
                                            $statusColor = $statusColors[$order['status']] ?? '#6b7280';
                                            ?>
                                            <span class="status-badge" style="background-color: <?= $statusColor ?>;">
                                                <?= ucfirst(escape($order['status'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="order-details">
                                        <div class="detail-item">
                                            <i class="fas fa-wine-bottle"></i>
                                            <?= intval($order['item_count']) ?> item(s)
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-rupee-sign"></i>
                                            ₹<?= number_format($order['total_amount'], 2) ?>
                                        </div>
                                    </div>
                                    <div class="order-actions">
                                        <a href="<?= url('order/' . $order['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state glass-card">
                            <div class="empty-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h3>No Orders Yet</h3>
                            <p>You haven't placed any orders. Start creating your custom water bottle today!</p>
                            <a href="<?= url('order/start') ?>" class="btn btn-primary">
                                <i class="fas fa-plus-circle"></i> Place Your First Order
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Quick Links Sidebar -->
                <div class="sidebar">
                    <div class="quick-links glass-card">
                        <h3><i class="fas fa-bolt"></i> Quick Links</h3>
                        <ul class="link-list">
                            <li>
                                <a href="<?= url('order/start') ?>">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Create New Order</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            <li>
                                <a href="<?= url('my-orders') ?>">
                                    <i class="fas fa-list"></i>
                                    <span>All Orders</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            <li>
                                <a href="<?= url('profile') ?>">
                                    <i class="fas fa-user-edit"></i>
                                    <span>Edit Profile</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="account-info glass-card">
                        <h3><i class="fas fa-user-circle"></i> Account Info</h3>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Name:</span>
                                <span class="value"><?= escape($user['name']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Email:</span>
                                <span class="value"><?= escape($user['email']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Member Since:</span>
                                <span class="value"><?= date('M Y', strtotime($user['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .dashboard-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding-bottom: 60px;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 0;
        margin-bottom: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .welcome-section h1 {
        margin: 0 0 10px;
        font-size: 32px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .welcome-section p {
        margin: 0;
        font-size: 16px;
        opacity: 0.9;
    }

    .stats-section {
        margin-bottom: 40px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 25px;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .stat-info h3 {
        margin: 0 0 5px;
        font-size: 32px;
        font-weight: 700;
        color: var(--dark);
    }

    .stat-info p {
        margin: 0;
        color: var(--text-light);
        font-size: 14px;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
    }

    .content-section {
        min-width: 0;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-header h2 {
        margin: 0;
        font-size: 24px;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .view-all-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .view-all-link:hover {
        color: var(--secondary);
    }

    .orders-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .order-card {
        padding: 20px;
        transition: all 0.3s;
    }

    .order-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }

    .order-info h3 {
        margin: 0 0 8px;
        font-size: 18px;
        color: var(--dark);
    }

    .order-date {
        font-size: 14px;
        color: var(--text-light);
    }

    .order-date i {
        margin-right: 5px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .order-details {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text);
        font-size: 14px;
    }

    .detail-item i {
        color: var(--primary);
    }

    .order-actions {
        display: flex;
        gap: 10px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 40px;
    }

    .empty-icon {
        font-size: 64px;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        margin: 0 0 10px;
        color: var(--dark);
    }

    .empty-state p {
        margin: 0 0 25px;
        color: var(--text-light);
    }

    .quick-links,
    .account-info {
        padding: 25px;
        margin-bottom: 20px;
    }

    .quick-links h3,
    .account-info h3 {
        margin: 0 0 20px;
        font-size: 18px;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .link-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .link-list li {
        margin-bottom: 5px;
    }

    .link-list a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        color: var(--text);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .link-list a:hover {
        background: rgba(102, 126, 234, 0.1);
        color: var(--primary);
    }

    .link-list a i:first-child {
        width: 20px;
        text-align: center;
        color: var(--primary);
    }

    .link-list a i:last-child {
        margin-left: auto;
        font-size: 12px;
        opacity: 0.5;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding-bottom: 12px;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item .label {
        color: var(--text-light);
        font-size: 14px;
    }

    .info-item .value {
        color: var(--dark);
        font-weight: 600;
        font-size: 14px;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .sidebar {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .quick-actions {
            width: 100%;
        }

        .quick-actions .btn {
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .sidebar {
            grid-template-columns: 1fr;
        }

        .welcome-section h1 {
            font-size: 24px;
        }

        .order-header {
            flex-direction: column;
            gap: 10px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            font-size: 28px;
        }

        .stat-info h3 {
            font-size: 28px;
        }
    }
</style>

<?php
$content = ob_get_clean();
$title = 'My Dashboard - Peepit';
include __DIR__ . '/../layouts/frontend.php';
?>
