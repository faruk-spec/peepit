<?php ob_start(); ?>

<div class="page-breadcrumb mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4>Bottle Sizes</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Bottle Sizes</li>
                </ol>
            </nav>
        </div>
        <a href="<?= url('admin/sizes/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i> Add New Size
        </a>
    </div>
</div>

<?php if (!empty($sizes)): ?>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Bottle Sizes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Size</th>
                            <th>Capacity (ml)</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sizes as $size): ?>
                            <tr>
                                <td><strong><?= escape($size['size']) ?></strong></td>
                                <td><?= number_format($size['capacity_ml']) ?> ml</td>
                                <td>
                                    <span class="badge bg-<?= $size['status'] === 'active' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($size['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d M Y', strtotime($size['created_at'])) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?= url('admin/sizes/edit/' . $size['id']) ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" 
                                              action="<?= url('admin/sizes/delete/' . $size['id']) ?>" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure? This may affect existing orders.');">
                                            <?= csrf_field() ?>
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
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <div style="font-size: 64px; color: #8897ad; margin-bottom: 20px;">
                <i class="fas fa-ruler-combined"></i>
            </div>
            <h3 class="mb-3">No Bottle Sizes Yet</h3>
            <p class="text-muted mb-4">Add bottle size options for customers</p>
            <a href="<?= url('admin/sizes/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Add First Size
            </a>
        </div>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$page_title = 'Bottle Sizes';
$current_page = 'bottles';
include __DIR__ . '/../../layouts/admin.php';
?>
