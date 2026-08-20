<?php ob_start(); ?>

<div class="page-breadcrumb mb-4">
    <h4>Order Management</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Orders</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">All Orders</h5>
        <div>
            <span class="badge bg-primary"><?= count($orders) ?> Total</span>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($orders)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
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
                        <?php foreach ($orders as $order): ?>
                            <?php
                            $badgeClass = 'secondary';
                            if ($order['status'] === 'completed') {
                                $badgeClass = 'success';
                            } elseif ($order['status'] === 'pending') {
                                $badgeClass = 'warning';
                            } elseif ($order['status'] === 'cancelled') {
                                $badgeClass = 'danger';
                            } elseif ($order['status'] === 'processing') {
                                $badgeClass = 'info';
                            }
                            ?>
                            <tr>
                                <td><strong><?= escape($order['order_number']) ?></strong></td>
                                <td>
                                    <div><?= escape($order['user_name']) ?></div>
                                    <small class="text-muted"><?= escape($order['user_email']) ?></small>
                                </td>
                                <td><strong>₹<?= number_format($order['total_amount'], 2) ?></strong></td>
                                <td>
                                    <span class="badge bg-<?= $badgeClass ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d M Y, g:i A', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <a href="<?= url('admin/orders/view/' . $order['id']) ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div style="font-size: 64px; color: #8897ad; margin-bottom: 20px;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="mb-3">No Orders Yet</h3>
                <p class="text-muted">Orders will appear here as customers place them</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$page_title = 'Order Management';
$current_page = 'orders';
include __DIR__ . '/../../layouts/admin.php';
?>
