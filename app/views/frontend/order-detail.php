<?php ob_start(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <div class="breadcrumb mb-30">
        <a href="<?= url('my-orders') ?>"><i class="fas fa-list-alt"></i> My Orders</a>
        <i class="fas fa-chevron-right"></i>
        <span>Order #<?= escape($order['order_number']) ?></span>
    </div>

    <div class="order-detail-header card glass-card mb-30">
        <div class="header-content">
            <div>
                <h1><i class="fas fa-receipt"></i> Order #<?= escape($order['order_number']) ?></h1>
                <p class="order-date">
                    <i class="fas fa-calendar"></i> Placed on <?= date('F d, Y \a\t g:i A', strtotime($order['created_at'])) ?>
                </p>
            </div>
            <div class="status-section">
                <span class="status-badge status-<?= escape($order['status']) ?>">
                    <?= ucfirst(escape($order['status'])) ?>
                </span>
            </div>
        </div>
    </div>

    <div class="order-content">
        <!-- Order Items -->
        <div class="card mb-30">
            <h2 class="mb-20"><i class="fas fa-shopping-bag"></i> Order Items</h2>
            <?php foreach ($items as $item): ?>
                <div class="order-item">
                    <div class="item-details">
                        <h3><?= escape($item['model_name']) ?> - <?= escape($item['bottle_size']) ?></h3>
                        <div class="item-specs">
                            <span class="spec-item">
                                <strong>Color:</strong>
                                <span class="color-dot" style="background: <?= escape($item['color']) ?>;"></span>
                                <?= escape($item['color']) ?>
                            </span>
                            <span class="spec-item">
                                <strong>Quantity:</strong> <?= escape($item['quantity']) ?>
                            </span>
                            <span class="spec-item">
                                <strong>Unit Price:</strong> ₹<?= number_format($item['unit_price'], 2) ?>
                            </span>
                        </div>
                        <?php if ($item['label_image']): ?>
                            <div class="label-preview mt-15">
                                <strong>Label Design:</strong>
                                <img src="<?= url('uploads/labels/' . escape($item['label_image'])) ?>" 
                                     alt="Label" 
                                     style="max-width: 200px; border-radius: 8px; margin-top: 10px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="item-price">
                        <strong>₹<?= number_format($item['total_price'], 2) ?></strong>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="detail-grid">
            <!-- Delivery Information -->
            <div class="card">
                <h2 class="mb-20"><i class="fas fa-truck"></i> Delivery Information</h2>
                <address class="delivery-address">
                    <?= nl2br(escape($order['delivery_address'])) ?><br>
                    <?= escape($order['delivery_city']) ?>, <?= escape($order['delivery_state']) ?><br>
                    PIN: <?= escape($order['delivery_pincode']) ?><br>
                    <strong>Phone:</strong> <?= escape($order['delivery_phone']) ?>
                </address>
                <?php if ($order['estimated_delivery']): ?>
                    <div class="estimated-delivery mt-20">
                        <i class="fas fa-clock"></i>
                        <strong>Estimated Delivery:</strong> 
                        <?= date('F d, Y', strtotime($order['estimated_delivery'])) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Payment Summary -->
            <div class="card">
                <h2 class="mb-20"><i class="fas fa-rupee-sign"></i> Payment Summary</h2>
                <div class="payment-details">
                    <div class="payment-row">
                        <span>Total Amount:</span>
                        <strong>₹<?= number_format($order['total_amount'], 2) ?></strong>
                    </div>
                    <div class="payment-row">
                        <span>Payment Status:</span>
                        <span class="payment-status">Pending</span>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($order['notes']): ?>
            <div class="card mt-30">
                <h2 class="mb-20"><i class="fas fa-sticky-note"></i> Notes</h2>
                <p><?= nl2br(escape($order['notes'])) ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-light);
        font-size: 14px;
    }

    .breadcrumb a {
        color: var(--primary);
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .order-detail-header {
        padding: 30px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .header-content h1 {
        margin: 0 0 10px;
        font-size: 28px;
    }

    .order-date {
        color: var(--text-light);
        margin: 0;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
    }

    .status-pending { background: #FEF3C7; color: #92400E; }
    .status-processing { background: #DBEAFE; color: #1E40AF; }
    .status-completed { background: #D1FAE5; color: #065F46; }
    .status-cancelled { background: #FEE2E2; color: #991B1B; }

    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 20px;
        border: 2px solid var(--border);
        border-radius: 12px;
        margin-bottom: 15px;
    }

    .order-item:last-child {
        margin-bottom: 0;
    }

    .item-details h3 {
        margin: 0 0 10px;
        color: var(--dark);
    }

    .item-specs {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .spec-item {
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--text);
        font-size: 14px;
    }

    .color-dot {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid var(--border);
        display: inline-block;
    }

    .item-price {
        font-size: 24px;
        color: var(--primary);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }

    .delivery-address {
        font-style: normal;
        line-height: 1.8;
        color: var(--text);
    }

    .estimated-delivery {
        padding: 15px;
        background: var(--light);
        border-radius: 8px;
        border-left: 4px solid var(--info);
    }

    .payment-details {
        display: grid;
        gap: 15px;
    }

    .payment-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
    }

    .payment-row:last-child {
        border-bottom: none;
    }

    .payment-status {
        color: var(--warning);
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 20px;
        }

        .order-item {
            flex-direction: column;
            gap: 15px;
        }

        .item-specs {
            flex-direction: column;
            gap: 8px;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
$content = ob_get_clean();
$title = 'Order Details - Peepit';
include __DIR__ . '/../layouts/frontend.php';
?>
