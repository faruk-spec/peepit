<?php ob_start(); ?>

<div class="page-breadcrumb mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4>Color Presets</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Color Presets</li>
                </ol>
            </nav>
        </div>
        <a href="<?= url('admin/colors/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i> Add New Color
        </a>
    </div>
</div>

<?php if (!empty($colors)): ?>
    <div class="row g-3">
        <?php foreach ($colors as $color): ?>
            <div class="col-12 col-md-6">
                <div class="card h-100" style="border-left: 5px solid <?= escape($color['hex_code']) ?>;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="rounded" style="width: 60px; height: 60px; background: <?= escape($color['hex_code']) ?>; border: 2px solid #e0e6ed;"></div>
                                    <div>
                                        <h5 class="mb-1"><?= escape($color['name']) ?></h5>
                                        <div class="text-muted small">
                                            <div><?= escape($color['hex_code']) ?></div>
                                            <div><?= escape($color['rgb_code']) ?></div>
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-<?= $color['status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($color['status']) ?>
                                </span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="<?= url('admin/colors/edit/' . $color['id']) ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" 
                                      action="<?= url('admin/colors/delete/' . $color['id']) ?>" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <div style="font-size: 64px; color: #8897ad; margin-bottom: 20px;">
                <i class="fas fa-palette"></i>
            </div>
            <h3 class="mb-3">No Color Presets Yet</h3>
            <p class="text-muted mb-4">Add color options for customers to choose from</p>
            <a href="<?= url('admin/colors/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Add First Color
            </a>
        </div>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$page_title = 'Color Presets';
$current_page = 'bottles';
include __DIR__ . '/../../layouts/admin.php';
?>
