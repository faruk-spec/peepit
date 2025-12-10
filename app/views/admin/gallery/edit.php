<?php ob_start(); ?>

<!-- Page Header -->
<div class="mb-4">
    <h1 class="h3 mb-2">Edit Gallery Image</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= url('admin/gallery') ?>">Gallery</a></li>
            <li class="breadcrumb-item active">Edit Image</li>
        </ol>
    </nav>
</div>

<!-- Edit Form -->
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Current Image</h5>
            </div>
            <div class="card-body">
                <img src="<?= url('uploads/gallery/' . htmlspecialchars($image['image_path'])) ?>" 
                     alt="<?= htmlspecialchars($image['caption'] ?? 'Gallery image') ?>"
                     class="img-fluid rounded">
                <div class="mt-3 text-muted small">
                    <div><strong>Uploaded:</strong> <?= date('M d, Y', strtotime($image['created_at'])) ?></div>
                    <?php if ($image['updated_at'] !== $image['created_at']): ?>
                        <div><strong>Modified:</strong> <?= date('M d, Y', strtotime($image['updated_at'])) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Image Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= url('admin/gallery/update/' . $image['id']) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <div class="mb-3">
                        <label for="caption" class="form-label">Caption</label>
                        <input type="text" 
                               class="form-control" 
                               id="caption" 
                               name="caption" 
                               value="<?= htmlspecialchars($image['caption'] ?? '') ?>" 
                               placeholder="Enter image caption">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Enter image description"><?= htmlspecialchars($image['description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <input type="number" 
                               class="form-control" 
                               id="priority" 
                               name="priority" 
                               value="<?= htmlspecialchars($image['priority']) ?>" 
                               min="0">
                        <small class="form-text text-muted">Lower numbers appear first in the gallery</small>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="is_enabled" 
                               name="is_enabled" 
                               <?= $image['is_enabled'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_enabled">
                            Enable on homepage
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Image
                        </button>
                        <a href="<?= url('admin/gallery') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
?>
