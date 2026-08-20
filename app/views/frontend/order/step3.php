<?php ob_start(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <!-- Progress Steps -->
    <div class="order-progress">
        <div class="step completed">
            <div class="step-number"><i class="fas fa-check"></i></div>
            <div class="step-label">Select Model</div>
        </div>
        <div class="step completed">
            <div class="step-number"><i class="fas fa-check"></i></div>
            <div class="step-label">Choose Size</div>
        </div>
        <div class="step active">
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
        <h2 class="mb-20"><i class="fas fa-palette"></i> Step 3: Select Color</h2>
        <p class="text-light mb-30">Choose a preset color or create your custom color</p>

        <form method="POST" action="<?= url('order/step4') ?>">
            <?= csrf_field() ?>

            <!-- Color Picker -->
            <div class="color-section mb-40">
                <h3 class="mb-20">Custom Color</h3>
                <div class="custom-color-picker">
                    <input type="color" 
                           name="color" 
                           id="colorPicker" 
                           value="#0EA5E9"
                           class="color-input">
                    <label for="colorPicker" class="color-picker-label">
                        <div class="color-preview" id="colorPreview"></div>
                        <span>Click to pick custom color</span>
                    </label>
                    <input type="text" 
                           id="colorHex" 
                           placeholder="#0EA5E9" 
                           class="form-control" 
                           style="max-width: 150px;">
                </div>
            </div>

            <!-- Preset Colors -->
            <?php if (!empty($colors)): ?>
                <div class="color-section">
                    <h3 class="mb-20">Preset Colors</h3>
                    <div class="color-presets">
                        <?php foreach ($colors as $color): ?>
                            <div class="color-preset-item">
                                <input type="radio" 
                                       name="color" 
                                       value="<?= escape($color['hex_code']) ?>" 
                                       id="color-<?= $color['id'] ?>"
                                       class="color-radio">
                                <label for="color-<?= $color['id'] ?>" 
                                       class="color-preset-label"
                                       style="background: <?= escape($color['hex_code']) ?>;">
                                    <span class="color-name"><?= escape($color['name']) ?></span>
                                    <div class="color-check"><i class="fas fa-check"></i></div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <a href="<?= url('order/step2') ?>" class="btn btn-outline">
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

    .color-section {
        padding: 20px;
        background: var(--light);
        border-radius: 12px;
    }

    .custom-color-picker {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .color-input {
        width: 80px;
        height: 80px;
        border: 3px solid var(--border);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .color-input:hover {
        border-color: var(--primary);
        transform: scale(1.05);
    }

    .color-picker-label {
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        font-weight: 500;
        color: var(--text);
    }

    .color-preview {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        border: 3px solid var(--border);
        background: #0EA5E9;
    }

    .color-presets {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
    }

    .color-preset-item {
        position: relative;
    }

    .color-radio {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .color-preset-label {
        display: block;
        height: 100px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        border: 3px solid transparent;
        overflow: hidden;
    }

    .color-preset-label:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: var(--dark);
    }

    .color-radio:checked + .color-preset-label {
        border-color: var(--dark);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .color-name {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 8px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
    }

    .color-check {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
        background: rgba(255, 255, 255, 0.9);
        color: var(--success);
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .color-radio:checked + .color-preset-label .color-check {
        display: flex;
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

        .color-presets {
            grid-template-columns: repeat(3, 1fr);
        }

        .form-actions {
            flex-direction: column;
        }
    }
</style>

<script>
    const colorPicker = document.getElementById('colorPicker');
    const colorHex = document.getElementById('colorHex');
    const colorPreview = document.getElementById('colorPreview');

    colorPicker.addEventListener('input', function() {
        const color = this.value;
        colorHex.value = color;
        colorPreview.style.background = color;
    });

    colorHex.addEventListener('input', function() {
        const color = this.value;
        if (/^#[0-9A-F]{6}$/i.test(color)) {
            colorPicker.value = color;
            colorPreview.style.background = color;
        }
    });

    // Initialize preview
    colorPreview.style.background = colorPicker.value;
    colorHex.value = colorPicker.value;
</script>

<?php
$content = ob_get_clean();
$title = 'Select Color - Order - Peepit';
include __DIR__ . '/../../layouts/frontend.php';
?>
