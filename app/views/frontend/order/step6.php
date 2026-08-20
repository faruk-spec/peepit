<?php ob_start(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <!-- Progress Steps -->
    <div class="order-progress">
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Model</div></div>
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Size</div></div>
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Color</div></div>
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Label</div></div>
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Quantity</div></div>
        <div class="step active"><div class="step-number">6</div><div class="step-label">Delivery</div></div>
        <div class="step"><div class="step-number">7</div><div class="step-label">Summary</div></div>
    </div>

    <div class="card glass-card" style="margin-top: 40px;">
        <h2 class="mb-20"><i class="fas fa-truck"></i> Step 6: Delivery Details</h2>
        <p class="text-light mb-30">Where should we deliver your custom bottles?</p>

        <form method="POST" action="<?= url('order/step7') ?>">
            <?= csrf_field() ?>

            <div class="form-grid">
                <div class="form-group">
                    <label for="address" class="required">Delivery Address</label>
                    <textarea name="address" 
                              id="address" 
                              class="form-control" 
                              rows="3" 
                              placeholder="Enter complete delivery address"
                              required><?= old('address') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city" class="required">City</label>
                        <input type="text" 
                               name="city" 
                               id="city" 
                               class="form-control" 
                               placeholder="City"
                               value="<?= old('city') ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="state" class="required">State</label>
                        <input type="text" 
                               name="state" 
                               id="state" 
                               class="form-control" 
                               placeholder="State"
                               value="<?= old('state') ?>"
                               required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="pincode" class="required">PIN Code</label>
                        <input type="text" 
                               name="pincode" 
                               id="pincode" 
                               class="form-control" 
                               placeholder="PIN Code"
                               value="<?= old('pincode') ?>"
                               pattern="[0-9]{6}"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="required">Contact Phone</label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               class="form-control" 
                               placeholder="+91 XXXXX XXXXX"
                               value="<?= old('phone') ?>"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Special Instructions (Optional)</label>
                    <textarea name="notes" 
                              id="notes" 
                              class="form-control" 
                              rows="2" 
                              placeholder="Any special delivery instructions?"><?= old('notes') ?></textarea>
                </div>
            </div>

            <div class="delivery-info-box mt-30">
                <div class="info-icon"><i class="fas fa-info-circle"></i></div>
                <div class="info-content">
                    <h4>Delivery Information</h4>
                    <ul>
                        <li><i class="fas fa-check"></i> Standard delivery: 7-10 business days</li>
                        <li><i class="fas fa-check"></i> Express delivery: 3-5 business days (additional charges apply)</li>
                        <li><i class="fas fa-check"></i> Free delivery on orders above 100 bottles</li>
                        <li><i class="fas fa-check"></i> Track your order in real-time after dispatch</li>
                    </ul>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= url('order/step5') ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    Continue <i class="fas fa-arrow-right"></i>
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

    .form-grid { max-width: 800px; margin: 0 auto; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .required::after { content: '*'; color: var(--error); margin-left: 5px; }

    .delivery-info-box { background: var(--light); border-left: 4px solid var(--info); padding: 20px; border-radius: 8px; display: flex; gap: 20px; }
    .info-icon { font-size: 32px; color: var(--info); }
    .info-content h4 { margin: 0 0 15px; color: var(--dark); }
    .info-content ul { list-style: none; padding: 0; margin: 0; }
    .info-content ul li { padding: 5px 0; color: var(--text); }
    .info-content ul li i { color: var(--success); margin-right: 10px; }

    .form-actions { display: flex; justify-content: space-between; gap: 15px; margin-top: 30px; padding-top: 30px; border-top: 2px solid var(--border); }

    @media (max-width: 768px) {
        .order-progress { overflow-x: auto; padding-bottom: 10px; }
        .step { min-width: 70px; }
        .step-number { width: 45px; height: 45px; font-size: 16px; }
        .step-label { font-size: 11px; }
        .form-row { grid-template-columns: 1fr; }
        .delivery-info-box { flex-direction: column; }
        .form-actions { flex-direction: column; }
    }
</style>

<?php
$content = ob_get_clean();
$title = 'Delivery Details - Order - Peepit';
include __DIR__ . '/../../layouts/frontend.php';
?>
