<?php
$current_page = 'bottles';
$title = 'Assign Pricing - ' . htmlspecialchars($bottle['name']);
include __DIR__ . '/../../layouts/admin.php';
?>

<style>
    .pricing-form-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 0 auto;
    }

    .bottle-info {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(99, 102, 241, 0.1));
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        border-left: 4px solid var(--primary);
    }

    .bottle-info h3 {
        margin: 0 0 10px 0;
        color: var(--dark);
        font-size: 24px;
    }

    .bottle-info p {
        margin: 5px 0;
        color: var(--gray);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
    }

    .form-group select,
    .form-group input {
        width: 100%;
        padding: 12px;
        border: 2px solid var(--border);
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s;
    }

    .form-group select:focus,
    .form-group input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }

    .help-text {
        display: block;
        margin-top: 5px;
        font-size: 14px;
        color: var(--gray);
    }

    .current-pricing {
        background: #e0f2fe;
        border: 2px solid #0ea5e9;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .current-pricing .label {
        font-weight: 600;
        color: #0369a1;
        margin-bottom: 5px;
    }

    .current-pricing .value {
        font-size: 18px;
        color: var(--dark);
    }

    .button-group {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: #0284c7;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
    }

    .btn-secondary {
        background: var(--gray);
        color: white;
    }

    .btn-secondary:hover {
        background: #64748b;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .pricing-form-card {
            padding: 20px;
            margin: 0 15px;
        }

        .button-group {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .bottle-info h3 {
            font-size: 20px;
        }
    }
</style>

<div class="pricing-form-card">
    <!-- Flash Messages -->
    <?php if (has_flash()): ?>
        <?php $flash_type = flash_type(); ?>
        <?php $flash_message = get_flash(); ?>
        <div class="alert alert-<?= $flash_type ?>" style="margin-bottom: 20px;">
            <i class="fas fa-<?= $flash_type === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
            <?= $flash_message ?>
        </div>
    <?php endif; ?>

    <!-- Bottle Information -->
    <div class="bottle-info">
        <h3><?= htmlspecialchars($bottle['name']) ?></h3>
        <?php if (!empty($bottle['description'])): ?>
            <p><?= htmlspecialchars($bottle['description']) ?></p>
        <?php endif; ?>
        <p><strong>Status:</strong> <span class="badge badge-<?= $bottle['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($bottle['status']) ?></span></p>
    </div>

    <!-- Current Pricing Display -->
    <?php if ($currentPricingTierId): ?>
        <?php
        $currentTier = null;
        foreach ($pricingTiers as $tier) {
            if ($tier['id'] == $currentPricingTierId) {
                $currentTier = $tier;
                break;
            }
        }
        ?>
        <?php if ($currentTier): ?>
            <div class="current-pricing">
                <div class="label">Current Pricing Tier:</div>
                <div class="value">
                    <?= htmlspecialchars($currentTier['description'] ?? 'Tier ' . $currentTier['id']) ?> 
                    - ₹<?= number_format($currentTier['price_per_unit'], 2) ?> per unit
                    <?php if ($currentTier['discount_percent'] > 0): ?>
                        (<?= $currentTier['discount_percent'] ?>% discount)
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="current-pricing" style="background: #fef3c7; border-color: #f59e0b;">
            <div class="label" style="color: #b45309;">Current Pricing:</div>
            <div class="value">Using General Bottle Pricing</div>
        </div>
    <?php endif; ?>

    <!-- Pricing Assignment Form -->
    <form method="POST" action="<?= url("admin/bottles/{$bottle['id']}/pricing/save") ?>">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

        <div class="form-group">
            <label for="pricing_tier_id">Assign Pricing Tier</label>
            <select name="pricing_tier_id" id="pricing_tier_id" required>
                <option value="general" <?= !$currentPricingTierId ? 'selected' : '' ?>>Use General Pricing (Default)</option>
                <option disabled>──────────────</option>
                <?php foreach ($pricingTiers as $tier): ?>
                    <option value="<?= $tier['id'] ?>" <?= $currentPricingTierId == $tier['id'] ? 'selected' : '' ?>>
                        <?php if (!empty($tier['description'])): ?>
                            <?= htmlspecialchars($tier['description']) ?>
                        <?php else: ?>
                            Qty <?= $tier['min_quantity'] ?><?= $tier['max_quantity'] ? '-' . $tier['max_quantity'] : '+' ?>
                        <?php endif; ?>
                        - ₹<?= number_format($tier['price_per_unit'], 2) ?>
                        <?php if ($tier['discount_percent'] > 0): ?>
                            (<?= $tier['discount_percent'] ?>% off)
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="help-text">
                Select a pricing tier for this bottle model, or use general pricing for standard rates.
            </span>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Pricing Assignment
            </button>
            <a href="<?= url('admin/bottles') ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>

    <!-- Info Box -->
    <div style="margin-top: 30px; padding: 15px; background: #f0f9ff; border-radius: 8px; border-left: 4px solid #0ea5e9;">
        <h4 style="margin: 0 0 10px 0; color: #0369a1;">
            <i class="fas fa-info-circle"></i> Pricing Priority
        </h4>
        <p style="margin: 0; color: #64748b; font-size: 14px;">
            <strong>1.</strong> Custom Bottle Pricing (if set in Bottle Model Pricing page)<br>
            <strong>2.</strong> Assigned Pricing Tier (this page)<br>
            <strong>3.</strong> General Bottle Pricing (default)
        </p>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/admin.php'; ?>
