<?php ob_start(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <!-- Progress Steps -->
    <div class="order-progress">
        <div class="step active">
            <div class="step-number">1</div>
            <div class="step-label">Select Model</div>
        </div>
        <div class="step">
            <div class="step-number">2</div>
            <div class="step-label">Choose Size</div>
        </div>
        <div class="step">
            <div class="step-number">3</div>
            <div class="step-label">Select Color</div>
        </div>
        <div class="step">
            <div class="step-number">4</div>
            <div class="step-label">Design Label</div>
        </div>
        <div class="step">
            <div class="step-number">5</div>
            <div class="step-label">Quantity</div>
        </div>
        <div class="step">
            <div class="step-number">6</div>
            <div class="step-label">Delivery</div>
        </div>
        <div class="step">
            <div class="step-number">7</div>
            <div class="step-label">Summary</div>
        </div>
    </div>

    <div class="card" style="margin-top: 40px;">
        <div class="card-header">
            <h2>Step 1: Select Bottle Model(s)</h2>
            <p>Choose one or more bottle models for your order</p>
        </div>

        <form method="POST" action="<?= url('order/step2') ?>" id="bottle-model-form">
            <?= csrf_field() ?>

            <?php if (!empty($models)): ?>
                <div class="bottle-models-grid">
                    <?php foreach ($models as $model): ?>
                        <div class="bottle-model-card">
                            <input type="checkbox" 
                                   name="bottle_models[]" 
                                   value="<?= $model['id'] ?>" 
                                   id="model-<?= $model['id'] ?>"
                                   class="bottle-checkbox">
                            <label for="model-<?= $model['id'] ?>" class="bottle-label">
                                <div class="bottle-image">
                                    <?php if ($model['image']): ?>
                                        <img src="<?= url('uploads/bottles/' . escape($model['image'])) ?>" 
                                             alt="<?= escape($model['name']) ?>">
                                    <?php else: ?>
                                        <div class="bottle-placeholder">🚰</div>
                                    <?php endif; ?>
                                </div>
                                <div class="bottle-info">
                                    <h3><?= escape($model['name']) ?></h3>
                                    <?php if ($model['description']): ?>
                                        <p><?= escape($model['description']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="bottle-check">✓</div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="form-actions" style="margin-top: 30px; text-align: center;">
                    <button type="submit" class="btn btn-primary btn-lg">
                        Continue to Size Selection →
                    </button>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <p>No bottle models available at the moment. Please check back later.</p>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<style>
    .order-progress {
        display: flex;
        justify-content: space-between;
        max-width: 900px;
        margin: 0 auto;
        position: relative;
    }

    .order-progress::before {
        content: '';
        position: absolute;
        top: 30px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e2e8f0;
        z-index: -1;
    }

    .step {
        flex: 1;
        text-align: center;
    }

    .step-number {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: white;
        border: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
        font-size: 20px;
        color: #718096;
    }

    .step.active .step-number {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .step-label {
        font-size: 14px;
        color: #718096;
    }

    .step.active .step-label {
        color: var(--primary-color);
        font-weight: 600;
    }

    .bottle-models-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        padding: 30px;
    }

    .bottle-model-card {
        position: relative;
    }

    .bottle-checkbox {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .bottle-label {
        display: block;
        background: white;
        border: 3px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }

    .bottle-label:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .bottle-checkbox:checked + .bottle-label {
        border-color: var(--primary-color);
        background: #f7fafc;
    }

    .bottle-image {
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        background: #f7fafc;
        border-radius: 5px;
    }

    .bottle-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .bottle-placeholder {
        font-size: 80px;
    }

    .bottle-info h3 {
        margin: 0 0 10px 0;
        font-size: 18px;
        color: var(--dark);
    }

    .bottle-info p {
        margin: 0;
        font-size: 14px;
        color: #718096;
        line-height: 1.5;
    }

    .bottle-check {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
        background: var(--success-color);
        color: white;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .bottle-checkbox:checked + .bottle-label .bottle-check {
        display: flex;
    }

    .btn-lg {
        padding: 15px 40px;
        font-size: 18px;
    }

    @media (max-width: 768px) {
        .order-progress {
            overflow-x: auto;
        }

        .step {
            min-width: 80px;
        }

        .step-number {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .step-label {
            font-size: 12px;
        }

        .bottle-models-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
document.getElementById('bottle-model-form').addEventListener('submit', function(e) {
    const checked = document.querySelectorAll('.bottle-checkbox:checked');
    if (checked.length === 0) {
        e.preventDefault();
        alert('Please select at least one bottle model');
    }
});
</script>

<?php
$content = ob_get_clean();
$title = 'Select Bottle Model - Order - Peepit';
include __DIR__ . '/../../layouts/frontend.php';
?>
