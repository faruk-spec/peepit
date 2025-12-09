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
        max-width: 1000px;
        margin: 0 auto 30px;
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

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 14px;
    }

    /* Custom Pricing Tiers Section */
    .custom-pricing-section {
        margin-top: 40px;
        padding-top: 40px;
        border-top: 2px solid var(--border);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .section-header h3 {
        margin: 0;
        color: var(--dark);
        font-size: 22px;
    }

    .tier-card {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
    }

    .tier-card:hover {
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.1);
    }

    .tier-card.inactive {
        opacity: 0.6;
        background: #f1f5f9;
    }

    .tier-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
    }

    .tier-qty {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary);
    }

    .tier-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .tier-status.active {
        background: #dcfce7;
        color: #166534;
    }

    .tier-status.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .tier-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .tier-detail-item label {
        display: block;
        font-size: 12px;
        color: var(--gray);
        margin-bottom: 4px;
    }

    .tier-detail-item value {
        display: block;
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
    }

    .tier-actions {
        display: flex;
        gap: 10px;
    }

    .add-tier-form {
        background: #f0f9ff;
        border: 2px dashed #0ea5e9;
        border-radius: 8px;
        padding: 30px;
        margin-top: 20px;
    }

    .add-tier-form h4 {
        margin: 0 0 20px 0;
        color: var(--dark);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--gray);
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .pricing-form-card {
            padding: 20px;
            margin: 0 15px 20px;
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

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .tier-header {
            flex-direction: column;
            gap: 10px;
        }

        .tier-details {
            grid-template-columns: 1fr;
        }

        .tier-actions {
            width: 100%;
        }

        .tier-actions .btn {
            flex: 1;
        }

        .form-row {
            grid-template-columns: 1fr;
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
            <strong>1.</strong> Custom Bottle Pricing (set below with quantity ranges)<br>
            <strong>2.</strong> Assigned Pricing Tier (selected above)<br>
            <strong>3.</strong> General Bottle Pricing (default fallback)
        </p>
    </div>
</div>

<!-- Custom Pricing Tiers Section -->
<div class="pricing-form-card">
    <div class="custom-pricing-section">
        <div class="section-header">
            <h3><i class="fas fa-layer-group"></i> Custom Quantity-Based Pricing</h3>
            <button type="button" class="btn btn-success btn-sm" onclick="toggleAddTierForm()">
                <i class="fas fa-plus"></i> Add Pricing Tier
            </button>
        </div>

        <p style="color: var(--gray); margin-bottom: 25px;">
            Set custom pricing for specific quantity ranges. Example: 1-10 bottles at ₹35, 11-25 at ₹32, 26+ at ₹30.
        </p>

        <?php if (empty($customTiers)): ?>
            <div class="empty-state">
                <i class="fas fa-tags"></i>
                <p>No custom pricing tiers set for this bottle.</p>
                <p style="font-size: 14px;">Click "Add Pricing Tier" to create quantity-based pricing.</p>
            </div>
        <?php else: ?>
            <?php foreach ($customTiers as $tier): ?>
                <div class="tier-card <?= $tier['is_active'] ? 'active' : 'inactive' ?>">
                    <div class="tier-header">
                        <div>
                            <div class="tier-qty">
                                <?= $tier['min_quantity'] ?><?= $tier['max_quantity'] ? '-' . $tier['max_quantity'] : '+' ?> bottles
                            </div>
                        </div>
                        <span class="tier-status <?= $tier['is_active'] ? 'active' : 'inactive' ?>">
                            <?= $tier['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>

                    <div class="tier-details">
                        <div class="tier-detail-item">
                            <label>Price Per Unit</label>
                            <value>₹<?= number_format($tier['price_per_unit'], 2) ?></value>
                        </div>
                        <?php if ($tier['discount_percent'] > 0): ?>
                            <div class="tier-detail-item">
                                <label>Discount</label>
                                <value><?= $tier['discount_percent'] ?>% off</value>
                            </div>
                        <?php endif; ?>
                        <?php if ($tier['valid_from'] || $tier['valid_until']): ?>
                            <div class="tier-detail-item">
                                <label>Valid Period</label>
                                <value>
                                    <?= $tier['valid_from'] ? date('M d, Y', strtotime($tier['valid_from'])) : 'Any' ?> 
                                    → 
                                    <?= $tier['valid_until'] ? date('M d, Y', strtotime($tier['valid_until'])) : 'Any' ?>
                                </value>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tier-actions">
                        <button type="button" class="btn btn-primary btn-sm" onclick="editTier(<?= $tier['id'] ?>, <?= htmlspecialchars(json_encode($tier), ENT_QUOTES, 'UTF-8') ?>)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form method="POST" action="<?= url("admin/bottles/{$bottle['id']}/pricing/tiers/{$tier['id']}/delete") ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this pricing tier?');">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Add/Edit Tier Form -->
        <div id="add-tier-form" class="add-tier-form" style="display: none;">
            <h4><i class="fas fa-plus-circle"></i> <span id="form-title">Add New Pricing Tier</span></h4>
            
            <form id="tier-form" method="POST" action="<?= url("admin/bottles/{$bottle['id']}/pricing/tiers/save") ?>">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" id="tier-id" name="tier_id" value="">

                <div class="form-row">
                    <div class="form-group">
                        <label for="min_quantity">Min Quantity *</label>
                        <input type="number" id="min_quantity" name="min_quantity" min="1" required>
                        <span class="help-text">Starting quantity for this tier</span>
                    </div>

                    <div class="form-group">
                        <label for="max_quantity">Max Quantity</label>
                        <input type="number" id="max_quantity" name="max_quantity" min="1">
                        <span class="help-text">Leave empty for unlimited (e.g., 51+)</span>
                    </div>

                    <div class="form-group">
                        <label for="price_per_unit">Price Per Unit (₹) *</label>
                        <input type="number" id="price_per_unit" name="price_per_unit" step="0.01" min="0.01" required>
                        <span class="help-text">Price for each bottle in this range</span>
                    </div>

                    <div class="form-group">
                        <label for="discount_percent">Discount %</label>
                        <input type="number" id="discount_percent" name="discount_percent" step="0.01" min="0" max="100" value="0">
                        <span class="help-text">For display only (optional)</span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="valid_from">Valid From (Optional)</label>
                        <input type="date" id="valid_from" name="valid_from">
                        <span class="help-text">Start date for seasonal pricing</span>
                    </div>

                    <div class="form-group">
                        <label for="valid_until">Valid Until (Optional)</label>
                        <input type="date" id="valid_until" name="valid_until">
                        <span class="help-text">End date for seasonal pricing</span>
                    </div>

                    <div class="form-group">
                        <label for="is_active">Status</label>
                        <select id="is_active" name="is_active">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <span class="help-text">Enable or disable this tier</span>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <span id="save-btn-text">Save Pricing Tier</span>
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="toggleAddTierForm()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleAddTierForm() {
    const form = document.getElementById('add-tier-form');
    if (form.style.display === 'none') {
        form.style.display = 'block';
        // Reset form
        document.getElementById('tier-form').reset();
        document.getElementById('tier-id').value = '';
        document.getElementById('form-title').textContent = 'Add New Pricing Tier';
        document.getElementById('save-btn-text').textContent = 'Save Pricing Tier';
        document.getElementById('tier-form').action = '<?= url("admin/bottles/{$bottle['id']}/pricing/tiers/save") ?>';
    } else {
        form.style.display = 'none';
    }
}

function editTier(tierId, tierData) {
    const form = document.getElementById('add-tier-form');
    form.style.display = 'block';
    
    // Update form title and action
    document.getElementById('form-title').textContent = 'Edit Pricing Tier';
    document.getElementById('save-btn-text').textContent = 'Update Pricing Tier';
    document.getElementById('tier-form').action = '<?= url("admin/bottles/{$bottle['id']}/pricing/tiers/") ?>' + tierId + '/update';
    
    // Populate form fields
    document.getElementById('tier-id').value = tierId;
    document.getElementById('min_quantity').value = tierData.min_quantity;
    document.getElementById('max_quantity').value = tierData.max_quantity || '';
    document.getElementById('price_per_unit').value = tierData.price_per_unit;
    document.getElementById('discount_percent').value = tierData.discount_percent || 0;
    document.getElementById('valid_from').value = tierData.valid_from || '';
    document.getElementById('valid_until').value = tierData.valid_until || '';
    document.getElementById('is_active').value = tierData.is_active;
    
    // Scroll to form
    form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
</script>

<?php include __DIR__ . '/../../layouts/admin.php'; ?>
