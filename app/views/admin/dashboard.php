<?php ob_start(); ?>

<div class="page-breadcrumb">
    <h4>Dashboard</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="widget-icon bg-gradient-purple">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="widget-info">
                        <p>Total Users</p>
                        <h3><?= number_format($stats['total_users']) ?></h3>
                        <div class="progress-info">
                            <i class="fas fa-arrow-up"></i>
                            <span>Active accounts</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="widget-icon bg-gradient-success">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="widget-info">
                        <p>Total Orders</p>
                        <h3><?= number_format($stats['total_orders']) ?></h3>
                        <div class="progress-info">
                            <i class="fas fa-chart-line"></i>
                            <span>All time</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="widget-icon bg-gradient-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="widget-info">
                        <p>Pending Orders</p>
                        <h3><?= number_format($stats['pending_orders']) ?></h3>
                        <div class="progress-info" style="color: #dc3545;">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Needs attention</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="widget-icon bg-gradient-danger">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="widget-info">
                        <p>Total Revenue</p>
                        <h3>₹<?= number_format($stats['total_revenue'], 2) ?></h3>
                        <div class="progress-info">
                            <i class="fas fa-chart-line"></i>
                            <span>Lifetime</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Recent Orders</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($recent_orders)): ?>
            <div class="table-responsive">
                <table class="table">
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
                                    <span class="badge bg-<?= $order['status'] === 'completed' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <a href="<?= url('admin/orders/view/' . $order['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-muted py-5">No orders yet</p>
        <?php endif; ?>
    </div>
</div>

<?php if (has_role('manager')): ?>
    <div class="row g-3 mt-3">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= url('admin/bottles/create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i> Add Bottle Model
                        </a>
                        <a href="<?= url('admin/templates/create') ?>" class="btn btn-secondary">
                            <i class="fas fa-plus-circle me-2"></i> Add Label Template
                        </a>
                        <a href="<?= url('admin/colors/create') ?>" class="btn btn-success">
                            <i class="fas fa-plus-circle me-2"></i> Add Color Preset
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">System Status</h5>
                </div>
                <div class="card-body">
                    <?php if (has_role('superadmin')): ?>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Database:</span>
                            <span class="badge bg-success">Connected</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>PHP Version:</span>
                            <span class="badge bg-info"><?= PHP_VERSION ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span>Upload Directory:</span>
                            <span class="badge bg-<?= is_writable(__DIR__ . '/../../../public/uploads') ? 'success' : 'danger' ?>">
                                <?= is_writable(__DIR__ . '/../../../public/uploads') ? 'Writable' : 'Not Writable' ?>
                            </span>
                        </div>
                    <?php else: ?>
                        <p class="text-center mb-0">System status: ✅ Operational</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$page_title = 'Dashboard';
$current_page = 'dashboard';
include __DIR__ . '/../layouts/admin.php';
?>
