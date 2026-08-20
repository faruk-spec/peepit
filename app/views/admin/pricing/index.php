<?php
$title = 'Price Setup - Admin Panel';
$current_page = 'pricing';
ob_start();
?>

<div class="admin-header-actions">
    <a href="<?= url('admin/pricing/tiers?action=create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Create New Tier
    </a>
</div>

<div class="glass-card">
    <div class="card-header">
        <h2>Pricing Tiers</h2>
        <p>Manage your product pricing tiers and discounts</p>
    </div>

    <?php if (empty($tiers)): ?>
        <div class="empty-state">
            <i class="fas fa-dollar-sign"></i>
            <h3>No Pricing Tiers Found</h3>
            <p>Get started by creating your first pricing tier.</p>
            <a href="<?= url('admin/pricing/tiers?action=create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Pricing Tier
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product Type</th>
                        <th>Quantity Range</th>
                        <th>Price/Unit</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tiers as $tier): ?>
                        <tr>
                            <td>
                                <span class="badge badge-<?= $tier['product_type'] === 'bottle' ? 'primary' : ($tier['product_type'] === 'label' ? 'info' : ($tier['product_type'] === 'printing' ? 'warning' : 'secondary')) ?>">
                                    <?= ucfirst(escape($tier['product_type'])) ?>
                                </span>
                            </td>
                            <td>
                                <?= escape($tier['min_quantity']) ?> - <?= $tier['max_quantity'] ? escape($tier['max_quantity']) : '∞' ?>
                            </td>
                            <td>₹<?= number_format($tier['price_per_unit'], 2) ?></td>
                            <td>
                                <?php if ($tier['discount_percent'] > 0): ?>
                                    <span class="text-success">
                                        <i class="fas fa-tag"></i> <?= number_format($tier['discount_percent'], 1) ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($tier['is_active']): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= url('admin/pricing/tiers?action=edit&id=' . $tier['id']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="<?= url('admin/pricing/tiers/delete') ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this pricing tier?');">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                        <input type="hidden" name="id" value="<?= $tier['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
    .admin-header-actions {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-primary {
        background: #0ea5e9;
        color: white;
    }

    .badge-info {
        background: #06b6d4;
        color: white;
    }

    .badge-warning {
        background: #f59e0b;
        color: white;
    }

    .badge-secondary {
        background: #6b7280;
        color: white;
    }

    .badge-success {
        background: #10b981;
        color: white;
    }

    .badge-danger {
        background: #ef4444;
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }

    .btn-outline-primary {
        border: 1px solid var(--primary);
        color: var(--primary);
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: var(--primary);
        color: white;
    }

    .btn-outline-danger {
        border: 1px solid #ef4444;
        color: #ef4444;
        background: transparent;
    }

    .btn-outline-danger:hover {
        background: #ef4444;
        color: white;
    }

    .text-success {
        color: #10b981;
    }

    .text-muted {
        color: #6b7280;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 64px;
        color: var(--primary);
        opacity: 0.5;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        margin: 0 0 10px;
        color: var(--dark);
    }

    .empty-state p {
        color: var(--text-light);
        margin-bottom: 30px;
    }

    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }

        .btn-sm {
            width: 100%;
        }

        .admin-table {
            font-size: 13px;
        }

        .admin-table th,
        .admin-table td {
            padding: 10px;
        }
    }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
?>
