<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
    <?php require_once __DIR__ . '/../../layouts/admin.php'; ?>

    <div class="admin-content">
        <div class="container-narrow">
            <div class="page-header">
                <h1>📄 Edit Page</h1>
                <p class="text-muted">Update page content and settings</p>
            </div>

            <?php if (has_flash()): ?>
                <div class="alert alert-<?= flash_type() ?>">
                    <?= get_flash() ?>
                </div>
            <?php endif; ?>

            <div class="glass-card">
                <form method="POST" action="<?= url('admin/pages/update/' . $page['id']) ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                    <div class="form-group">
                        <label for="title">Page Title <span style="color: red;">*</span></label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="form-control" 
                               required
                               placeholder="Enter page title"
                               value="<?= htmlspecialchars($page['title']) ?>">
                        <small class="text-muted">This will be displayed as the page heading</small>
                    </div>

                    <div class="form-group">
                        <label for="slug">URL Slug <span style="color: red;">*</span></label>
                        <input type="text" 
                               id="slug" 
                               name="slug" 
                               class="form-control" 
                               required
                               placeholder="page-url-slug"
                               pattern="[a-z0-9-]+"
                               value="<?= htmlspecialchars($page['slug']) ?>">
                        <small class="text-muted">Only lowercase letters, numbers, and hyphens. Current URL: /page/<?= htmlspecialchars($page['slug']) ?></small>
                    </div>

                    <div class="form-group">
                        <label for="content">Page Content</label>
                        <textarea id="content" 
                                  name="content" 
                                  class="form-control" 
                                  rows="15"
                                  placeholder="Enter page content (HTML supported)"><?= htmlspecialchars($page['content']) ?></textarea>
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
                               value="<?= htmlspecialchars($page['meta_title'] ?? '') ?>">
                        <small class="text-muted">Optional: Override the page title for search engines (60 characters recommended)</small>
                    </div>

                    <div class="form-group">
                        <label for="meta_description">Meta Description (SEO)</label>
                        <textarea id="meta_description" 
                                  name="meta_description" 
                                  class="form-control" 
                                  rows="3"
                                  maxlength="500"
                                  placeholder="Brief description for search engines"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
                        <small class="text-muted">Optional: Description shown in search results (155 characters recommended)</small>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="draft" <?= $page['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= $page['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                        </select>
                        <small class="text-muted">Only published pages will be visible on the website</small>
                    </div>

                    <div class="form-actions" style="display: flex; gap: 10px; margin-top: 30px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Page
                        </button>
                        <?php if ($page['status'] === 'published'): ?>
                            <a href="<?= url('page/' . $page['slug']) ?>" class="btn btn-secondary" target="_blank">
                                <i class="fas fa-eye"></i> View Page
                            </a>
                        <?php endif; ?>
                        <a href="<?= url('admin/pages') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>

            <div class="glass-card" style="margin-top: 20px;">
                <h3 style="margin-bottom: 15px;">Page Information</h3>
                <div style="display: grid; gap: 10px;">
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                        <span style="font-weight: 600;">Created:</span>
                        <span><?= date('M d, Y H:i', strtotime($page['created_at'])) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                        <span style="font-weight: 600;">Last Updated:</span>
                        <span><?= date('M d, Y H:i', strtotime($page['updated_at'])) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                        <span style="font-weight: 600;">Page ID:</span>
                        <span><?= $page['id'] ?></span>
                    </div>
                </div>
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
</body>
</html>
