<?php
$page_title = 'Price Setup';
$current_page = 'pricing';
ob_start();
?>

<div class="page-breadcrumb mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4>Pricing Tiers</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pricing</li>
                </ol>
            </nav>
        </div>
        <a href="<?= url('admin/pricing/tiers?action=create') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i> Create New Tier
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Pricing Tiers</h5>
    </div>
    <div class="card-body">
        <?php if (empty($tiers)): ?>
            <div class="text-center py-5">
                <div style="font-size: 64px; color: #8897ad; margin-bottom: 20px;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h3 class="mb-3">No Pricing Tiers Found</h3>
                <p class="text-muted mb-4">Get started by creating your first pricing tier.</p>
                <a href="<?= url('admin/pricing/tiers?action=create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Create Pricing Tier
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
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
                                    <?php
                                    $badgeClass = 'secondary';
                                    if ($tier['product_type'] === 'bottle') $badgeClass = 'primary';
                                    elseif ($tier['product_type'] === 'label') $badgeClass = 'info';
                                    elseif ($tier['product_type'] === 'printing') $badgeClass = 'warning';
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>">
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
                                    <span class="badge bg-<?= $tier['is_active'] ? 'success' : 'danger' ?>">
                                        <?= $tier['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?= url('admin/pricing/tiers?action=edit&id=' . $tier['id']) ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" 
                                              action="<?= url('admin/pricing/tiers/delete') ?>" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this pricing tier?');">
                                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                            <input type="hidden" name="id" value="<?= $tier['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
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
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
?>
