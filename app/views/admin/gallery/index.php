<?php ob_start(); ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">Gallery Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
                <li class="breadcrumb-item active">Gallery</li>
            </ol>
        </nav>
    </div>
    <a href="<?= url('admin/gallery/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Images
    </a>
</div>

<!-- Gallery Grid -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Gallery Images</h5>
    </div>
    <div class="card-body">
        <?php if (empty($images)): ?>
            <div class="text-center py-5">
                <i class="fas fa-images fa-4x text-muted mb-3"></i>
                <p class="text-muted">No images in gallery yet.</p>
                <a href="<?= url('admin/gallery/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add First Image
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 100px">Image</th>
                            <th>Caption</th>
                            <th>Description</th>
                            <th style="width: 80px">Priority</th>
                            <th style="width: 100px">Status</th>
                            <th style="width: 150px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($images as $image): ?>
                            <tr>
                                <td>
                                    <img src="<?= url('uploads/gallery/' . htmlspecialchars($image['image_path'])) ?>" 
                                         alt="<?= htmlspecialchars($image['caption'] ?? 'Gallery image') ?>"
                                         class="img-thumbnail"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                </td>
                                <td><?= htmlspecialchars($image['caption'] ?: '-') ?></td>
                                <td>
                                    <?php if ($image['description']): ?>
                                        <?= htmlspecialchars(substr($image['description'], 0, 50)) . (strlen($image['description']) > 50 ? '...' : '') ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($image['priority']) ?></td>
                                <td>
                                    <?php if ($image['is_enabled']): ?>
                                        <span class="badge bg-success">Enabled</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Disabled</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= url('admin/gallery/edit/' . $image['id']) ?>" 
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="<?= url('admin/gallery/toggle/' . $image['id']) ?>" 
                                              style="display: inline;">
                                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                            <button type="submit" class="btn btn-outline-warning" 
                                                    title="<?= $image['is_enabled'] ? 'Disable' : 'Enable' ?>">
                                                <i class="fas fa-<?= $image['is_enabled'] ? 'eye-slash' : 'eye' ?>"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="<?= url('admin/gallery/delete/' . $image['id']) ?>" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this image?');">
                                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
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
