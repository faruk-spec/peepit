<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Hero Slide - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
    <?php require_once __DIR__ . '/../../layouts/admin.php'; ?>

    <div class="admin-content">
        <div class="container-narrow">
            <div class="page-header">
                <h1>🖼️ Create Hero Slide</h1>
                <p class="text-muted">Add a new slide to the homepage hero section</p>
            </div>

            <?php if (has_flash()): ?>
                <div class="alert alert-<?= flash_type() ?>">
                    <?= get_flash() ?>
                </div>
            <?php endif; ?>

            <div class="glass-card">
                <form method="POST" action="<?= url('admin/hero-slider/store') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                    <div class="form-group">
                        <label for="image">Hero Image <span style="color: red;">*</span></label>
                        <input type="file" 
                               id="image" 
                               name="image" 
                               class="form-control" 
                               accept="image/jpeg,image/jpg,image/png,image/webp"
                               required
                               onchange="previewImage(this)">
                        <small class="text-muted">Recommended: 1920x1080px (16:9 ratio), Max 5MB, Formats: JPEG, PNG, WebP</small>
                        <div id="imagePreview" style="margin-top: 15px; display: none;">
                            <img id="preview" src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title">Title (Optional)</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="form-control" 
                               placeholder="Override default hero title"
                               value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                        <small class="text-muted">Leave empty to use default hero title from homepage settings</small>
                    </div>

                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <textarea id="description" 
                                  name="description" 
                                  class="form-control" 
                                  rows="3"
                                  placeholder="Override default hero description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        <small class="text-muted">Leave empty to use default hero description</small>
                    </div>

                    <div class="form-group">
                        <label for="image_alt">Image Alt Text</label>
                        <input type="text" 
                               id="image_alt" 
                               name="image_alt" 
                               class="form-control" 
                               placeholder="Describe the image for accessibility"
                               value="<?= htmlspecialchars($_POST['image_alt'] ?? '') ?>">
                        <small class="text-muted">For SEO and accessibility</small>
                    </div>

                    <div class="form-group">
                        <label for="button_text">Button Text (Optional)</label>
                        <input type="text" 
                               id="button_text" 
                               name="button_text" 
                               class="form-control" 
                               placeholder="Get Started"
                               value="<?= htmlspecialchars($_POST['button_text'] ?? '') ?>">
                        <small class="text-muted">Override default button text</small>
                    </div>

                    <div class="form-group">
                        <label for="button_url">Button URL (Optional)</label>
                        <input type="text" 
                               id="button_url" 
                               name="button_url" 
                               class="form-control" 
                               placeholder="/register, /order/start"
                               value="<?= htmlspecialchars($_POST['button_url'] ?? '') ?>">
                        <small class="text-muted">Override default button URL</small>
                    </div>

                    <div class="form-group">
                        <label for="order">Order</label>
                        <input type="number" 
                               id="order" 
                               name="order" 
                               class="form-control" 
                               min="0"
                               value="<?= htmlspecialchars($_POST['order'] ?? '0') ?>">
                        <small class="text-muted">Lower numbers appear first in the slider (0 = first)</small>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active" <?= ($_POST['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($_POST['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <small class="text-muted">Only active slides will be displayed</small>
                    </div>

                    <div class="form-actions" style="display: flex; gap: 10px; margin-top: 30px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Hero Slide
                        </button>
                        <a href="<?= url('admin/hero-slider') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column !important;
            }
            
            .form-actions .btn {
                width: 100%;
            }
        }
    </style>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const previewDiv = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewDiv.style.display = 'block';
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
