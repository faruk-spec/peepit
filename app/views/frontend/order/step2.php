<?php ob_start(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <!-- Progress Steps -->
    <div class="order-progress">
        <div class="step completed">
            <div class="step-number"><i class="fas fa-check"></i></div>
            <div class="step-label">Select Model</div>
        </div>
        <div class="step active">
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

    <div class="card glass-card" style="margin-top: 40px;">
        <h2 class="mb-20"><i class="fas fa-ruler-combined"></i> Step 2: Choose Bottle Size</h2>
        <p class="text-light mb-30">Select the capacity that fits your needs</p>

        <form method="POST" action="<?= url('order/step3') ?>">
            <?= csrf_field() ?>

            <?php if (!empty($sizes)): ?>
                <div class="size-grid">
                    <?php foreach ($sizes as $size): ?>
                        <div class="size-card">
                            <input type="radio" 
                                   name="size_id" 
                                   value="<?= $size['id'] ?>" 
                                   id="size-<?= $size['id'] ?>"
                                   class="size-radio" required>
                            <label for="size-<?= $size['id'] ?>" class="size-label">
                                <div class="size-icon">
                                    <i class="fas fa-wine-bottle"></i>
                                </div>
                                <h3><?= escape($size['size']) ?></h3>
                                <p class="capacity"><?= escape($size['capacity_ml']) ?>ml</p>
                                <div class="size-check"><i class="fas fa-check-circle"></i></div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="form-actions">
                    <a href="<?= url('order/start') ?>" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Continue <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            <?php else: ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i> No bottle sizes available at the moment.
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
        padding: 0 20px;
    }

    .order-progress::before {
        content: '';
        position: absolute;
        top: 30px;
        left: 40px;
        right: 40px;
        height: 3px;
        background: var(--border);
        z-index: 0;
    }

    .step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .step-number {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
        font-size: 20px;
        color: var(--text-light);
        position: relative;
        z-index: 1;
    }

    .step.active .step-number {
        background: var(--gradient-primary);
        color: white;
        border-color: var(--primary);
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
    }

    .step.completed .step-number {
        background: var(--success);
        color: white;
        border-color: var(--success);
    }

    .step-label {
        font-size: 13px;
        color: var(--text-light);
        font-weight: 500;
    }

    .step.active .step-label {
        color: var(--primary);
        font-weight: 600;
    }

    .size-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .size-card {
        position: relative;
    }

    .size-radio {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .size-label {
        display: block;
        background: white;
        border: 3px solid var(--border);
        border-radius: 16px;
        padding: 30px 20px;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        position: relative;
    }

    .size-label:hover {
        border-color: var(--primary);
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.2);
    }

    .size-radio:checked + .size-label {
        border-color: var(--primary);
        background: var(--light);
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.15);
    }

    .size-icon {
        font-size: 48px;
        color: var(--primary);
        margin-bottom: 15px;
    }

    .size-label h3 {
        margin: 10px 0 5px;
        font-size: 20px;
        color: var(--dark);
    }

    .capacity {
        font-size: 14px;
        color: var(--text-light);
        margin: 0;
    }

    .size-check {
        position: absolute;
        top: 10px;
        right: 10px;
        color: var(--success);
        font-size: 24px;
        display: none;
    }

    .size-radio:checked + .size-label .size-check {
        display: block;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid var(--border);
    }

    @media (max-width: 768px) {
        .order-progress {
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .step {
            min-width: 70px;
        }

        .step-number {
            width: 45px;
            height: 45px;
            font-size: 16px;
        }

        .step-label {
            font-size: 11px;
        }

        .size-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .form-actions {
            flex-direction: column;
        }
    }
</style>

<?php
$content = ob_get_clean();
$title = 'Choose Size - Order - Peepit';
include __DIR__ . '/../../layouts/frontend.php';
?>
