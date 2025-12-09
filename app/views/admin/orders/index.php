<?php ob_start(); ?>

<div class="mb-30">
    <h2 style="margin: 0 0 5px;">Order Management</h2>
    <p style="margin: 0; color: var(--text-light);">View and manage customer orders</p>
</div>

<div class="card admin-table">
    <?php if (!empty($orders)): ?>
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
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong><?= escape($order['order_number']) ?></strong></td>
                        <td>
                            <div><?= escape($order['user_name']) ?></div>
                            <small style="color: var(--text-light);"><?= escape($order['user_email']) ?></small>
                        </td>
                        <td><strong>₹<?= number_format($order['total_amount'], 2) ?></strong></td>
                        <td>
                            <span class="status-badge status-<?= $order['status'] ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y, g:i A', strtotime($order['created_at'])) ?></td>
                        <td>
                            <a href="<?= url('admin/orders/view/' . $order['id']) ?>" 
                               class="btn btn-primary" 
                               style="padding: 6px 12px; font-size: 14px;">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="text-center" style="padding: 60px 20px;">
            <div style="font-size: 64px; color: var(--text-light); margin-bottom: 20px;">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3>No Orders Yet</h3>
            <p style="color: var(--text-light);">Orders will appear here as customers place them</p>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
$page_title = 'Order Management';
$current_page = 'orders';
include __DIR__ . '/../../layouts/admin.php';
?>
