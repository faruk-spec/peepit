<?php ob_start(); ?>

<!-- Page Header -->
<div class="mb-4">
    <h1 class="h3 mb-2">Add Gallery Images</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= url('admin/gallery') ?>">Gallery</a></li>
            <li class="breadcrumb-item active">Add Images</li>
        </ol>
    </nav>
</div>

<!-- Upload Form -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Upload Images</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= url('admin/gallery/store') ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Requirements:</strong>
                <ul class="mb-0 mt-2">
                    <li>Images must be square (1:1 aspect ratio, e.g., 500x500, 1000x1000)</li>
                    <li>Supported formats: JPG, PNG, GIF, WebP</li>
                    <li>You can upload multiple images at once</li>
                    <li>All uploaded images will share the same caption/description (edit individually later)</li>
                </ul>
            </div>

            <div class="mb-3">
                <label for="images" class="form-label">Select Images *</label>
                <input type="file" 
                       class="form-control" 
                       id="images" 
                       name="images[]" 
                       accept="image/*" 
                       multiple 
                       required
                       onchange="previewImages(event)">
                <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple files</small>
            </div>

            <div id="imagePreview" class="row g-3 mb-3"></div>

            <div class="mb-3">
                <label for="caption" class="form-label">Caption (Optional)</label>
                <input type="text" 
                       class="form-control" 
                       id="caption" 
                       name="caption" 
                       placeholder="Enter image caption">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description (Optional)</label>
                <textarea class="form-control" 
                          id="description" 
                          name="description" 
                          rows="3" 
                          placeholder="Enter image description"></textarea>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">Starting Priority</label>
                <input type="number" 
                       class="form-control" 
                       id="priority" 
                       name="priority" 
                       value="<?= $next_priority ?>" 
                       min="0">
                <small class="form-text text-muted">Lower numbers appear first. Multiple images will increment from this value.</small>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" 
                       class="form-check-input" 
                       id="is_enabled" 
                       name="is_enabled" 
                       checked>
                <label class="form-check-label" for="is_enabled">
                    Enable images on homepage
                </label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Upload Images
                </button>
                <a href="<?= url('admin/gallery') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImages(event) {
    const files = event.target.files;
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';

    if (files.length === 0) return;

    Array.from(files).forEach((file, index) => {
        if (!file.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                const aspectRatio = this.width / this.height;
                const isSquare = Math.abs(aspectRatio - 1) < 0.1;
                
                const col = document.createElement('div');
                col.className = 'col-md-3';
                col.innerHTML = `
                    <div class="card ${!isSquare ? 'border-danger' : ''}">
                        <img src="${e.target.result}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-2">
                            <small class="text-muted">${file.name}</small><br>
                            <small class="${isSquare ? 'text-success' : 'text-danger'}">
                                ${this.width}x${this.height}
                                ${!isSquare ? ' (Not square!)' : ' ✓'}
                            </small>
                        </div>
                    </div>
                `;
                preview.appendChild(col);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
?>
