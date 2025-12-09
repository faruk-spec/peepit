<?php ob_start(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <h1 class="mb-30"><i class="fas fa-list-alt"></i> My Orders</h1>

    <?php if (!empty($orders)): ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card card">
                    <div class="order-header">
                        <div class="order-info">
                            <h3>Order #<?= escape($order['order_number']) ?></h3>
                            <span class="order-date">
                                <i class="fas fa-calendar"></i> 
                                <?= date('F d, Y', strtotime($order['created_at'])) ?>
                            </span>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-<?= escape($order['status']) ?>">
                                <?= ucfirst(escape($order['status'])) ?>
                            </span>
                        </div>
                    </div>

                    <div class="order-body">
                        <div class="order-details">
                            <div class="detail-item">
                                <i class="fas fa-box"></i>
                                <span><?= escape($order['item_count']) ?> item(s)</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-rupee-sign"></i>
                                <span>₹<?= number_format($order['total_amount'], 2) ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= escape($order['delivery_city']) ?>, <?= escape($order['delivery_state']) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="order-footer">
                        <a href="<?= url('order/' . $order['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <?php if ($order['status'] === 'pending'): ?>
                            <a href="<?= url('order/' . $order['id'] . '/cancel') ?>" 
                               class="btn btn-outline"
                               onclick="return confirm('Are you sure you want to cancel this order?')">
                                <i class="fas fa-times"></i> Cancel Order
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-shopping-cart"></i></div>
            <h2>No Orders Yet</h2>
            <p>You haven't placed any orders yet. Start by creating your custom water bottle!</p>
            <a href="<?= url('order/start') ?>" class="btn btn-primary btn-lg mt-20">
                <i class="fas fa-plus-circle"></i> Start New Order
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
    .orders-list {
        display: grid;
        gap: 20px;
    }

    .order-card {
        transition: all 0.3s;
    }

    .order-card:hover {
        transform: translateY(-3px);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border);
        margin-bottom: 15px;
    }

    .order-info h3 {
        margin: 0 0 8px;
        color: var(--dark);
        font-size: 20px;
    }

    .order-date {
        color: var(--text-light);
        font-size: 14px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
    }

    .status-pending {
        background: #FEF3C7;
        color: #92400E;
    }

    .status-processing {
        background: #DBEAFE;
        color: #1E40AF;
    }

    .status-completed {
        background: #D1FAE5;
        color: #065F46;
    }

    .status-cancelled {
        background: #FEE2E2;
        color: #991B1B;
    }

    .order-body {
        margin-bottom: 15px;
    }

    .order-details {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text);
        font-size: 15px;
    }

    .detail-item i {
        color: var(--primary);
        width: 20px;
    }

    .order-footer {
        display: flex;
        gap: 10px;
        padding-top: 15px;
        border-top: 2px solid var(--border);
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-icon {
        font-size: 80px;
        color: var(--text-light);
        margin-bottom: 20px;
    }

    .empty-state h2 {
        margin-bottom: 15px;
        color: var(--dark);
    }

    .empty-state p {
        color: var(--text-light);
        font-size: 16px;
        margin-bottom: 30px;
    }

    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            gap: 15px;
        }

        .order-details {
            flex-direction: column;
            gap: 12px;
        }

        .order-footer {
            flex-direction: column;
        }

        .order-footer .btn {
            width: 100%;
        }
    }
</style>

<?php
$content = ob_get_clean();
$title = 'My Orders - Peepit';
include __DIR__ . '/../layouts/frontend.php';
?>
