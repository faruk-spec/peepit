<?php ob_start(); ?>

<div class="mb-30">
    <a href="<?= url('admin/orders') ?>" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>

<div class="card glass-card mb-30">
    <div style="display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h2 style="margin: 0 0 10px;">Order #<?= escape($order['order_number']) ?></h2>
            <p style="margin: 0; color: var(--text-light);">
                Placed on <?= date('F d, Y \a\t g:i A', strtotime($order['created_at'])) ?>
            </p>
        </div>
        <span class="status-badge status-<?= $order['status'] ?>" style="font-size: 16px; padding: 8px 20px;">
            <?= ucfirst($order['status']) ?>
        </span>
    </div>
</div>

<div class="grid grid-2" style="gap: 30px;">
    <!-- Order Items -->
    <div class="card">
        <h3 class="mb-20"><i class="fas fa-shopping-bag"></i> Order Items</h3>
        <?php foreach ($items as $item): ?>
            <div style="padding: 15px; background: var(--light); border-radius: 8px; margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between;">
                    <div>
                        <strong><?= escape($item['model_name']) ?> - <?= escape($item['bottle_size']) ?></strong>
                        <div style="font-size: 13px; color: var(--text-light); margin-top: 5px;">
                            Color: <span style="display: inline-block; width: 20px; height: 20px; background: <?= escape($item['color']) ?>; border: 2px solid var(--border); border-radius: 50%; vertical-align: middle;"></span> <?= escape($item['color']) ?>
                        </div>
                        <div style="font-size: 13px; color: var(--text-light);">
                            Quantity: <?= escape($item['quantity']) ?> × ₹<?= number_format($item['unit_price'], 2) ?>
                        </div>
                    </div>
                    <div style="font-size: 18px; font-weight: bold; color: var(--primary);">
                        ₹<?= number_format($item['total_price'], 2) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div style="border-top: 2px solid var(--border); padding-top: 15px; margin-top: 15px;">
            <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: bold;">
                <span>Total:</span>
                <span style="color: var(--primary);">₹<?= number_format($order['total_amount'], 2) ?></span>
            </div>
        </div>
    </div>

    <!-- Customer & Delivery Info -->
    <div>
        <div class="card mb-20">
            <h3 class="mb-15"><i class="fas fa-user"></i> Customer Information</h3>
            <div style="line-height: 1.8;">
                <strong><?= escape($order['user_name']) ?></strong><br>
                <i class="fas fa-envelope" style="width: 20px;"></i> <?= escape($order['user_email']) ?><br>
                <i class="fas fa-phone" style="width: 20px;"></i> <?= escape($order['user_phone']) ?>
            </div>
        </div>

        <div class="card mb-20">
            <h3 class="mb-15"><i class="fas fa-truck"></i> Delivery Address</h3>
            <address style="font-style: normal; line-height: 1.8;">
                <?= nl2br(escape($order['delivery_address'])) ?><br>
                <?= escape($order['delivery_city']) ?>, <?= escape($order['delivery_state']) ?><br>
                PIN: <?= escape($order['delivery_pincode']) ?><br>
                <strong>Phone:</strong> <?= escape($order['delivery_phone']) ?>
            </address>
        </div>

        <!-- Update Status Form -->
        <div class="card">
            <h3 class="mb-15"><i class="fas fa-edit"></i> Update Order Status</h3>
            <form method="POST" action="<?= url('admin/orders/update-status/' . $order['id']) ?>">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="status">Order Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes">Notes (Optional)</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3"><?= escape($order['notes'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update Status
                </button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$page_title = 'Order Details';
$current_page = 'orders';
include __DIR__ . '/../../layouts/admin.php';
?>
