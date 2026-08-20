<?php
$page_title = 'Bottle Model Pricing';
$current_page = 'pricing';

// Load pricing helper
require_once __DIR__ . '/../../../helpers/pricing_helper.php';

ob_start();
?>

<div class="page-breadcrumb mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4>Bottle Model Pricing</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('admin/pricing') ?>">Pricing</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Bottle Models</li>
                </ol>
            </nav>
        </div>
        <a href="<?= url('admin/pricing') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Pricing
        </a>
    </div>
</div>

<!-- Pricing Statistics -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="widget-icon bg-gradient-purple">
                        <i class="fas fa-wine-bottle"></i>
                    </div>
                    <div class="widget-info">
                        <p>Total Bottles</p>
                        <h3><?= count($bottles ?? []) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="widget-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="widget-info">
                        <p>Custom Pricing</p>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="widget-icon bg-gradient-success">
                        <i class="fas fa-link"></i>
                    </div>
                    <div class="widget-info">
                        <p>Tier Assigned</p>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="widget-icon bg-gradient-warning">
                        <i class="fas fa-arrows-alt-h"></i>
                    </div>
                    <div class="widget-info">
                        <p>Using General</p>
                        <h3><?= count($bottles ?? []) - $customCount - $tierCount ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Bottle Models</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
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
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted">No bottle models found</p>
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
                                        <img src="<?= url('uploads/bottles/' . escape($bottle['image'])) ?>" 
                                             alt="<?= escape($bottle['name']) ?>" 
                                             class="rounded"
                                             style="width: 60px; height: 60px; object-fit: cover;">
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
                                    <span class="badge bg-<?= $color ?>"><?= $label ?></span>
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
                                    <div class="d-flex gap-2">
                                        <a href="<?= url('admin/bottles/edit/' . $bottle['id']) ?>" 
                                           class="btn btn-sm btn-info" 
                                           title="Edit Bottle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= url('admin/bottles/' . $bottle['id'] . '/pricing') ?>" 
                                           class="btn btn-sm btn-primary" 
                                           title="Manage Pricing">
                                            <i class="fas fa-dollar-sign me-1"></i> Pricing
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
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
?>
