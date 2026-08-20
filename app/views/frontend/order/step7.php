<?php ob_start(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <!-- Progress Steps -->
    <div class="order-progress">
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Model</div></div>
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Size</div></div>
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Color</div></div>
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Label</div></div>
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Quantity</div></div>
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Delivery</div></div>
        <div class="step active"><div class="step-number">7</div><div class="step-label">Summary</div></div>
    </div>

    <div class="card glass-card" style="margin-top: 40px;">
        <h2 class="mb-20"><i class="fas fa-check-circle"></i> Step 7: Order Summary</h2>
        <p class="text-light mb-30">Review your order details before confirmation</p>

        <?php
        $orderData = $_SESSION['order_data'] ?? [];
        ?>

        <div class="summary-grid">
            <!-- Order Details -->
            <div class="summary-section">
                <h3><i class="fas fa-shopping-bag"></i> Order Details</h3>
                <div class="summary-item">
                    <span class="label">Bottle Models:</span>
                    <span class="value"><?= count($orderData['models'] ?? []) ?> model(s) selected</span>
                </div>
                <div class="summary-item">
                    <span class="label">Size:</span>
                    <span class="value"><?= escape($orderData['size_id'] ?? 'N/A') ?></span>
                </div>
                <div class="summary-item">
                    <span class="label">Color:</span>
                    <span class="value">
                        <span class="color-preview" style="background: <?= escape($orderData['color'] ?? '#000') ?>;"></span>
                        <?= escape($orderData['color'] ?? 'N/A') ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="label">Label Design:</span>
                    <span class="value">
                        <?php 
                        if (isset($orderData['label_image'])) {
                            echo 'Custom Upload';
                        } elseif (isset($orderData['label_design'])) {
                            echo 'Template #' . $orderData['label_design'];
                        } else {
                            echo 'Plain';
                        }
                        ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="label">Quantity:</span>
                    <span class="value"><strong><?= escape($orderData['quantity'] ?? 0) ?> bottles</strong></span>
                </div>
            </div>

            <!-- Delivery Details -->
            <div class="summary-section">
                <h3><i class="fas fa-map-marker-alt"></i> Delivery Address</h3>
                <?php $delivery = $orderData['delivery'] ?? []; ?>
                <address class="delivery-address">
                    <?= escape($delivery['address'] ?? '') ?><br>
                    <?= escape($delivery['city'] ?? '') ?>, <?= escape($delivery['state'] ?? '') ?> - <?= escape($delivery['pincode'] ?? '') ?><br>
                    <strong>Phone:</strong> <?= escape($delivery['phone'] ?? '') ?>
                </address>
            </div>

            <!-- Pricing Summary -->
            <div class="summary-section pricing-summary">
                <h3><i class="fas fa-calculator"></i> Pricing</h3>
                <div class="price-row">
                    <span>Unit Price:</span>
                    <span>₹<?= number_format($orderData['unit_price'] ?? 0, 2) ?></span>
                </div>
                <div class="price-row">
                    <span>Quantity:</span>
                    <span>× <?= escape($orderData['quantity'] ?? 0) ?></span>
                </div>
                <div class="price-row subtotal">
                    <span>Subtotal:</span>
                    <span>₹<?= number_format(($orderData['unit_price'] ?? 0) * ($orderData['quantity'] ?? 0), 2) ?></span>
                </div>
                <div class="price-row">
                    <span>Delivery Charges:</span>
                    <span>₹0.00</span>
                </div>
                <div class="price-row total">
                    <span>Total Amount:</span>
                    <span>₹<?= number_format($orderData['total_price'] ?? 0, 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="terms-box mt-30">
            <label class="terms-label">
                <input type="checkbox" id="agreeTerms" required>
                <span>I agree to the <a href="<?= url('terms') ?>" target="_blank">Terms & Conditions</a> and <a href="<?= url('privacy') ?>" target="_blank">Privacy Policy</a></span>
            </label>
        </div>

        <!-- Actions -->
        <form method="POST" action="<?= url('order/submit') ?>">
            <?= csrf_field() ?>
            
            <div class="form-actions">
                <a href="<?= url('order/step6') ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-success btn-lg" id="submitOrder">
                    <i class="fas fa-check-circle"></i> Confirm & Place Order
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .order-progress { display: flex; justify-content: space-between; max-width: 900px; margin: 0 auto; position: relative; padding: 0 20px; }
    .order-progress::before { content: ''; position: absolute; top: 30px; left: 40px; right: 40px; height: 3px; background: var(--border); z-index: 0; }
    .step { flex: 1; text-align: center; position: relative; }
    .step-number { width: 60px; height: 60px; border-radius: 50%; background: white; border: 3px solid var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-weight: bold; font-size: 20px; color: var(--text-light); position: relative; z-index: 1; }
    .step.active .step-number { background: var(--gradient-primary); color: white; border-color: var(--primary); box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3); }
    .step.completed .step-number { background: var(--success); color: white; border-color: var(--success); }
    .step-label { font-size: 13px; color: var(--text-light); font-weight: 500; }
    .step.active .step-label { color: var(--primary); font-weight: 600; }

    .summary-grid { display: grid; gap: 30px; }
    .summary-section { background: var(--light); padding: 25px; border-radius: 12px; border-left: 4px solid var(--primary); }
    .summary-section h3 { margin: 0 0 20px; color: var(--dark); font-size: 18px; }
    .summary-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border); }
    .summary-item:last-child { border-bottom: none; }
    .summary-item .label { color: var(--text-light); font-weight: 500; }
    .summary-item .value { color: var(--dark); font-weight: 600; display: flex; align-items: center; gap: 10px; }
    .color-preview { width: 30px; height: 30px; border-radius: 50%; border: 2px solid var(--border); }

    .delivery-address { font-style: normal; line-height: 1.8; color: var(--text); margin: 0; }

    .pricing-summary { border-left-color: var(--success); }
    .price-row { display: flex; justify-content: space-between; padding: 10px 0; color: var(--text); }
    .price-row.subtotal { border-top: 1px solid var(--border); margin-top: 10px; padding-top: 15px; font-weight: 600; }
    .price-row.total { border-top: 2px solid var(--primary); margin-top: 10px; padding-top: 15px; font-size: 20px; font-weight: bold; color: var(--primary); }

    .terms-box { background: var(--light); padding: 20px; border-radius: 8px; border: 2px solid var(--border); }
    .terms-label { display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .terms-label input { width: 20px; height: 20px; cursor: pointer; }
    .terms-label a { color: var(--primary); text-decoration: underline; }

    .form-actions { display: flex; justify-content: space-between; gap: 15px; margin-top: 30px; padding-top: 30px; border-top: 2px solid var(--border); }
    .btn-lg { padding: 15px 35px; font-size: 18px; }

    @media (max-width: 768px) {
        .order-progress { overflow-x: auto; padding-bottom: 10px; }
        .step { min-width: 70px; }
        .step-number { width: 45px; height: 45px; font-size: 16px; }
        .step-label { font-size: 11px; }
        .summary-item { flex-direction: column; gap: 5px; }
        .form-actions { flex-direction: column; }
    }
</style>

<script>
    document.getElementById('submitOrder').addEventListener('click', function(e) {
        if (!document.getElementById('agreeTerms').checked) {
            e.preventDefault();
            alert('Please agree to the Terms & Conditions to continue');
        }
    });
</script>

<?php
$content = ob_get_clean();
$title = 'Order Summary - Order - Peepit';
include __DIR__ . '/../../layouts/frontend.php';
?>
