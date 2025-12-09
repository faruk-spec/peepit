<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Page - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
    <?php require_once __DIR__ . '/../../layouts/admin.php'; ?>

    <div class="admin-content">
        <div class="container-narrow">
            <div class="page-header">
                <h1>📄 Create New Page</h1>
                <p class="text-muted">Create a custom page for your website</p>
            </div>

            <?php if (has_flash()): ?>
                <div class="alert alert-<?= flash_type() ?>">
                    <?= get_flash() ?>
                </div>
            <?php endif; ?>

            <div class="glass-card">
                <form method="POST" action="<?= url('admin/pages/store') ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                    <div class="form-group">
                        <label for="title">Page Title <span style="color: red;">*</span></label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="form-control" 
                               required
                               placeholder="Enter page title"
                               value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                        <small class="text-muted">This will be displayed as the page heading</small>
                    </div>

                    <div class="form-group">
                        <label for="slug">URL Slug</label>
                        <input type="text" 
                               id="slug" 
                               name="slug" 
                               class="form-control" 
                               placeholder="page-url-slug"
                               pattern="[a-z0-9-]+"
                               value="<?= htmlspecialchars($_POST['slug'] ?? '') ?>">
                        <small class="text-muted">Leave empty to auto-generate from title. Only lowercase letters, numbers, and hyphens. Will be accessible at: /page/your-slug</small>
                    </div>

                    <div class="form-group">
                        <label for="content">Page Content</label>
                        <textarea id="content" 
                                  name="content" 
                                  class="form-control" 
                                  rows="15"
                                  placeholder="Enter page content (HTML supported)"><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                        <small class="text-muted">You can use HTML tags to format your content</small>
                    </div>

                    <div class="form-group">
                        <label for="meta_title">Meta Title (SEO)</label>
                        <input type="text" 
                               id="meta_title" 
                               name="meta_title" 
                               class="form-control" 
                               maxlength="255"
                               placeholder="SEO title for search engines"
                               value="<?= htmlspecialchars($_POST['meta_title'] ?? '') ?>">
                        <small class="text-muted">Optional: Override the page title for search engines (60 characters recommended)</small>
                    </div>

                    <div class="form-group">
                        <label for="meta_description">Meta Description (SEO)</label>
                        <textarea id="meta_description" 
                                  name="meta_description" 
                                  class="form-control" 
                                  rows="3"
                                  maxlength="500"
                                  placeholder="Brief description for search engines"><?= htmlspecialchars($_POST['meta_description'] ?? '') ?></textarea>
                        <small class="text-muted">Optional: Description shown in search results (155 characters recommended)</small>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="draft" <?= ($_POST['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= ($_POST['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                        </select>
                        <small class="text-muted">Only published pages will be visible on the website</small>
                    </div>

                    <div class="form-actions" style="display: flex; gap: 10px; margin-top: 30px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Page
                        </button>
                        <a href="<?= url('admin/pages') ?>" class="btn btn-secondary">
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
        // Auto-generate slug from title
        document.getElementById('title').addEventListener('input', function() {
            const slugInput = document.getElementById('slug');
            if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.value = slug;
                slugInput.dataset.autoGenerated = 'true';
            }
        });

        // Mark slug as manually edited
        document.getElementById('slug').addEventListener('input', function() {
            if (this.value) {
                this.dataset.autoGenerated = 'false';
            }
        });
    </script>
</body>
</html>
