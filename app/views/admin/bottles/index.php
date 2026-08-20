<?php ob_start(); ?>

<div class="page-breadcrumb mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4>Bottle Models</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Bottle Models</li>
                </ol>
            </nav>
        </div>
        <a href="<?= url('admin/bottles/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i> Add New Model
        </a>
    </div>
</div>

<?php if (!empty($bottles)): ?>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Bottle Models</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bottles as $bottle): ?>
                            <tr>
                                <td>
                                    <?php if ($bottle['image']): ?>
                                        <img src="<?= url('uploads/bottles/' . escape($bottle['image'])) ?>" 
                                             alt="<?= escape($bottle['name']) ?>"
                                             class="rounded"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded d-flex align-items-center justify-content-center bg-light" 
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-wine-bottle text-muted" style="font-size: 24px;"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= escape($bottle['name']) ?></strong></td>
                                <td><?= escape(substr($bottle['description'] ?? '', 0, 50)) ?><?= strlen($bottle['description'] ?? '') > 50 ? '...' : '' ?></td>
                                <td>
                                    <span class="badge bg-<?= $bottle['status'] === 'active' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($bottle['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d M Y', strtotime($bottle['created_at'])) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?= url('admin/bottles/edit/' . $bottle['id']) ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" 
                                              action="<?= url('admin/bottles/delete/' . $bottle['id']) ?>" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this bottle model?');">
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
                <i class="fas fa-wine-bottle"></i>
            </div>
            <h3 class="mb-3">No Bottle Models Yet</h3>
            <p class="text-muted mb-4">Start by adding your first bottle model</p>
            <a href="<?= url('admin/bottles/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Add First Model
            </a>
        </div>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$page_title = 'Bottle Models';
$current_page = 'bottles';
include __DIR__ . '/../../layouts/admin.php';
?>
