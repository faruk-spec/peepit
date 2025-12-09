<?php ob_start(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <!-- Progress Steps -->
    <div class="order-progress">
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Model</div></div>
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Size</div></div>
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Color</div></div>
        <div class="step completed"><div class="step-number"><i class="fas fa-check"></i></div><div class="step-label">Label</div></div>
        <div class="step active"><div class="step-number">5</div><div class="step-label">Quantity</div></div>
        <div class="step"><div class="step-number">6</div><div class="step-label">Delivery</div></div>
        <div class="step"><div class="step-number">7</div><div class="step-label">Summary</div></div>
    </div>

    <div class="card glass-card" style="margin-top: 40px;">
        <h2 class="mb-20"><i class="fas fa-shopping-cart"></i> Step 5: Choose Quantity</h2>
        <p class="text-light mb-30">Select how many bottles you need - bulk orders get better pricing!</p>

        <form method="POST" action="<?= url('order/step6') ?>">
            <?= csrf_field() ?>

            <div class="quantity-section">
                <div class="quantity-input-group">
                    <label for="quantity" class="form-label">Number of Bottles</label>
                    <div class="quantity-controls">
                        <button type="button" class="qty-btn" onclick="changeQuantity(-1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" 
                               name="quantity" 
                               id="quantity" 
                               class="form-control quantity-input" 
                               value="1" 
                               min="1" 
                               required>
                        <button type="button" class="qty-btn" onclick="changeQuantity(1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Pricing Tiers -->
                <div class="pricing-tiers mt-40">
                    <h3 class="mb-20"><i class="fas fa-tag"></i> Bulk Pricing</h3>
                    <div class="pricing-cards">
                        <div class="pricing-card" onclick="setQuantity(10)">
                            <div class="tier-qty">1-20</div>
                            <div class="tier-price">₹25.00</div>
                            <div class="tier-label">Per bottle</div>
                        </div>
                        <div class="pricing-card popular" onclick="setQuantity(30)">
                            <div class="badge">Popular</div>
                            <div class="tier-qty">21-50</div>
                            <div class="tier-price">₹22.00</div>
                            <div class="tier-label">Per bottle</div>
                            <div class="savings">Save 12%</div>
                        </div>
                        <div class="pricing-card" onclick="setQuantity(100)">
                            <div class="badge">Best Value</div>
                            <div class="tier-qty">51+</div>
                            <div class="tier-price">₹20.00</div>
                            <div class="tier-label">Per bottle</div>
                            <div class="savings">Save 20%</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Select Buttons -->
                <div class="quick-select mt-30">
                    <h4 class="mb-15">Quick Select:</h4>
                    <div class="quick-btns">
                        <button type="button" class="quick-btn" onclick="setQuantity(10)">10</button>
                        <button type="button" class="quick-btn" onclick="setQuantity(25)">25</button>
                        <button type="button" class="quick-btn" onclick="setQuantity(50)">50</button>
                        <button type="button" class="quick-btn" onclick="setQuantity(100)">100</button>
                        <button type="button" class="quick-btn" onclick="setQuantity(250)">250</button>
                        <button type="button" class="quick-btn" onclick="setQuantity(500)">500</button>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= url('order/step4') ?>" class="btn btn-outline">
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

    .quantity-section { padding: 20px; }
    .quantity-input-group { text-align: center; max-width: 400px; margin: 0 auto; }
    .form-label { display: block; font-size: 18px; font-weight: 600; margin-bottom: 20px; color: var(--dark); }
    .quantity-controls { display: flex; align-items: center; gap: 15px; justify-content: center; }
    .qty-btn { width: 50px; height: 50px; border: 2px solid var(--primary); background: white; color: var(--primary); border-radius: 50%; font-size: 20px; cursor: pointer; transition: all 0.3s; }
    .qty-btn:hover { background: var(--primary); color: white; transform: scale(1.1); }
    .quantity-input { width: 150px; height: 60px; text-align: center; font-size: 32px; font-weight: bold; border: 3px solid var(--border); border-radius: 12px; }
    .quantity-input:focus { border-color: var(--primary); outline: none; }

    .pricing-tiers { background: var(--light); padding: 30px; border-radius: 12px; }
    .pricing-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px; }
    .pricing-card { background: white; padding: 30px 20px; border-radius: 12px; border: 3px solid var(--border); text-align: center; cursor: pointer; transition: all 0.3s; position: relative; }
    .pricing-card:hover { border-color: var(--primary); transform: translateY(-5px); box-shadow: 0 8px 25px rgba(14, 165, 233, 0.2); }
    .pricing-card.popular { border-color: var(--primary); background: var(--light); }
    .badge { position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--gradient-primary); color: white; padding: 5px 15px; border-radius: 12px; font-size: 12px; font-weight: 600; }
    .tier-qty { font-size: 24px; font-weight: bold; color: var(--text); margin-bottom: 10px; }
    .tier-price { font-size: 36px; font-weight: bold; color: var(--primary); margin-bottom: 5px; }
    .tier-label { font-size: 14px; color: var(--text-light); }
    .savings { margin-top: 10px; color: var(--success); font-weight: 600; font-size: 14px; }

    .quick-select { text-align: center; }
    .quick-btns { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
    .quick-btn { padding: 10px 20px; border: 2px solid var(--border); background: white; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
    .quick-btn:hover { border-color: var(--primary); background: var(--light); color: var(--primary); }

    .form-actions { display: flex; justify-content: space-between; gap: 15px; margin-top: 30px; padding-top: 30px; border-top: 2px solid var(--border); }

    @media (max-width: 768px) {
        .order-progress { overflow-x: auto; padding-bottom: 10px; }
        .step { min-width: 70px; }
        .step-number { width: 45px; height: 45px; font-size: 16px; }
        .step-label { font-size: 11px; }
        .pricing-cards { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
    }
</style>

<script>
    function changeQuantity(delta) {
        const input = document.getElementById('quantity');
        const newValue = parseInt(input.value) + delta;
        if (newValue >= 1) {
            input.value = newValue;
        }
    }

    function setQuantity(qty) {
        document.getElementById('quantity').value = qty;
    }
</script>

<?php
$content = ob_get_clean();
$title = 'Choose Quantity - Order - Peepit';
include __DIR__ . '/../../layouts/frontend.php';
?>
