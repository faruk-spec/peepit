<?php
$current_page = 'pricing';
$page_title = 'Bottle Model Pricing';

// Load pricing helper
require_once __DIR__ . '/../../../helpers/pricing_helper.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Admin Panel</title>
</head>
<body>
<?php include __DIR__ . '/../../layouts/admin.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="flex-space-between">
            <div>
                <h1>💰 Bottle Model Pricing</h1>
                <p>Assign specific pricing tiers to individual bottle models</p>
            </div>
            <div class="button-group">
                <a href="/admin/pricing" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Pricing
                </a>
            </div>
        </div>
    </div>

    <?php if (has_flash()): ?>
        <?php $flash_type = flash_type(); $flash_message = get_flash(); ?>
        <div class="alert alert-<?= $flash_type ?>">
            <i class="fas fa-<?= $flash_type === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
            <?= escape($flash_message) ?>
        </div>
    <?php endif; ?>

    <div class="glass-card">
        <div class="card-header">
            <h2>All Bottle Models</h2>
            <p>View and manage pricing for each bottle model</p>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Bottle Model</th>
                        <th>Image</th>
                        <th>Current Pricing</th>
                        <th>Pricing Type</th>
                        <th>Price Range</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bottles)): ?>
                        <tr>
                            <td colspan="6" class="text-center empty-state">
                                <i class="fas fa-inbox fa-3x"></i>
                                <p>No bottle models found</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bottles as $bottle): ?>
                            <tr>
                                <td>
                                    <strong><?= escape($bottle['name']) ?></strong>
                                    <br>
                                    <small class="text-muted">#<?= $bottle['id'] ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($bottle['image'])): ?>
                                        <img src="/uploads/bottles/<?= escape($bottle['image']) ?>" 
                                             alt="<?= escape($bottle['name']) ?>" 
                                             class="bottle-thumbnail">
                                    <?php else: ?>
                                        <span class="text-muted">No image</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $pricing = calculateBottlePrice($bottle['id'], 1);
                                    ?>
                                    <strong>₹<?= number_format($pricing['price_per_unit'], 2) ?></strong>
                                    <br>
                                    <small class="text-muted">per unit</small>
                                </td>
                                <td>
                                    <?php
                                    $type = $pricing['pricing_type'] ?? 'general';
                                    $typeLabels = [
                                        'custom' => ['Custom Pricing', 'primary'],
                                        'tier' => ['Assigned Tier', 'info'],
                                        'general' => ['General Pricing', 'secondary'],
                                        'default' => ['Default', 'warning']
                                    ];
                                    [$label, $color] = $typeLabels[$type] ?? ['Unknown', 'secondary'];
                                    ?>
                                    <span class="badge badge-<?= $color ?>"><?= $label ?></span>
                                </td>
                                <td>
                                    <?php
                                    // Get pricing range for this bottle
                                    $customPricing = getBottleCustomPricing($bottle['id']);
                                    if (!empty($customPricing)):
                                        $minPrice = min(array_column($customPricing, 'price_per_unit'));
                                        $maxPrice = max(array_column($customPricing, 'price_per_unit'));
                                        if ($minPrice != $maxPrice):
                                            echo "₹" . number_format($minPrice, 2) . " - ₹" . number_format($maxPrice, 2);
                                        else:
                                            echo "₹" . number_format($minPrice, 2);
                                        endif;
                                    else:
                                        echo "See tier";
                                    endif;
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/admin/bottles/edit/<?= $bottle['id'] ?>" 
                                           class="btn btn-sm btn-info" 
                                           title="Edit Bottle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/admin/bottles/<?= $bottle['id'] ?>/pricing" 
                                           class="btn btn-sm btn-primary" 
                                           title="Manage Pricing">
                                            <i class="fas fa-dollar-sign"></i> Pricing
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pricing Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #0ea5e9;">
                <i class="fas fa-wine-bottle"></i>
            </div>
            <div class="stat-details">
                <h3><?= count($bottles ?? []) ?></h3>
                <p>Total Bottles</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #8b5cf6;">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-details">
                <h3>
                    <?php
                    $customCount = 0;
                    foreach ($bottles ?? [] as $bottle) {
                        if (!empty(getBottleCustomPricing($bottle['id']))) {
                            $customCount++;
                        }
                    }
                    echo $customCount;
                    ?>
                </h3>
                <p>Custom Pricing</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #10b981;">
                <i class="fas fa-link"></i>
            </div>
            <div class="stat-details">
                <h3>
                    <?php
                    $tierCount = 0;
                    foreach ($bottles ?? [] as $bottle) {
                        if (!empty($bottle['pricing_tier_id'])) {
                            $tierCount++;
                        }
                    }
                    echo $tierCount;
                    ?>
                </h3>
                <p>Tier Assigned</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #f59e0b;">
                <i class="fas fa-arrows-alt-h"></i>
            </div>
            <div class="stat-details">
                <h3><?= count($bottles ?? []) - $customCount - $tierCount ?></h3>
                <p>Using General</p>
            </div>
        </div>
    </div>
</div>

<style>
.bottle-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid rgba(255, 255, 255, 0.1);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-details h3 {
    font-size: 32px;
    font-weight: 700;
    margin: 0;
}

.stat-details p {
    margin: 0;
    color: rgba(255, 255, 255, 0.7);
    font-size: 14px;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

</body>
</html>
