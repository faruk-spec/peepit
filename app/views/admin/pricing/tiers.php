<?php
$title = ($action === 'edit' ? 'Edit' : 'Create') . ' Pricing Tier - Admin Panel';
$current_page = 'pricing';
ob_start();
?>

<div class="admin-header-actions">
    <a href="<?= url('admin/pricing') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Pricing
    </a>
</div>

<div class="glass-card">
    <div class="card-header">
        <h2><?= $action === 'edit' ? 'Edit' : 'Create New' ?> Pricing Tier</h2>
        <p>Set up pricing and discounts for your products</p>
    </div>

    <form method="POST" action="<?= url('admin/pricing/tiers/save') ?>" class="form-container">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <?php if ($action === 'edit' && isset($tier)): ?>
            <input type="hidden" name="id" value="<?= $tier['id'] ?>">
        <?php endif; ?>

        <div class="form-row">
            <div class="form-group">
                <label for="product_type">Product Type <span class="required">*</span></label>
                <select name="product_type" id="product_type" class="form-control" required>
                    <option value="">Select Product Type</option>
                    <option value="bottle" <?= isset($tier) && $tier['product_type'] === 'bottle' ? 'selected' : '' ?>>Bottle</option>
                    <option value="label" <?= isset($tier) && $tier['product_type'] === 'label' ? 'selected' : '' ?>>Label</option>
                    <option value="printing" <?= isset($tier) && $tier['product_type'] === 'printing' ? 'selected' : '' ?>>Printing</option>
                    <option value="setup" <?= isset($tier) && $tier['product_type'] === 'setup' ? 'selected' : '' ?>>Setup Fee</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="min_quantity">Minimum Quantity <span class="required">*</span></label>
                <input type="number" name="min_quantity" id="min_quantity" class="form-control" 
                       value="<?= isset($tier) ? escape($tier['min_quantity']) : '' ?>" 
                       min="1" required>
                <small class="form-text">Starting quantity for this pricing tier</small>
            </div>

            <div class="form-group">
                <label for="max_quantity">Maximum Quantity</label>
                <input type="number" name="max_quantity" id="max_quantity" class="form-control" 
                       value="<?= isset($tier) && $tier['max_quantity'] ? escape($tier['max_quantity']) : '' ?>" 
                       min="1">
                <small class="form-text">Leave empty for unlimited (∞)</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="price_per_unit">Price Per Unit (₹) <span class="required">*</span></label>
                <input type="number" name="price_per_unit" id="price_per_unit" class="form-control" 
                       value="<?= isset($tier) ? escape($tier['price_per_unit']) : '' ?>" 
                       step="0.01" min="0.01" required>
                <small class="form-text">Price in Indian Rupees (₹)</small>
            </div>

            <div class="form-group">
                <label for="discount_percent">Discount Percentage (%)</label>
                <input type="number" name="discount_percent" id="discount_percent" class="form-control" 
                       value="<?= isset($tier) ? escape($tier['discount_percent']) : '0' ?>" 
                       step="0.1" min="0" max="100">
                <small class="form-text">Percentage discount for this tier (0-100)</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" 
                           <?= isset($tier) && $tier['is_active'] ? 'checked' : 'checked' ?>>
                    Active (Available for orders)
                </label>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Pricing Tier
            </button>
            <a href="<?= url('admin/pricing') ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<style>
    .form-container {
        padding: 20px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--dark);
        font-size: 14px;
    }

    .required {
        color: #ef4444;
    }

    .form-control {
        padding: 12px 16px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }

    .form-text {
        font-size: 12px;
        color: var(--text-light);
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        margin-top: 20px;
    }

    .admin-header-actions {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin.php';
?>
