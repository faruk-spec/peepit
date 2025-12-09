<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Navigation Item - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
    <?php require_once __DIR__ . '/../../layouts/admin.php'; ?>

    <div class="admin-content">
        <div class="container-narrow">
            <div class="page-header">
                <h1>🧭 Edit Navigation Item</h1>
                <p class="text-muted">Update navigation menu item</p>
            </div>

            <?php if (has_flash()): ?>
                <div class="alert alert-<?= flash_type() ?>">
                    <?= get_flash() ?>
                </div>
            <?php endif; ?>

            <div class="glass-card">
                <form method="POST" action="<?= url('admin/navigation/update/' . $item['id']) ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                    <div class="form-group">
                        <label for="label">Label <span style="color: red;">*</span></label>
                        <input type="text" 
                               id="label" 
                               name="label" 
                               class="form-control" 
                               required
                               placeholder="Home, About Us, Contact, etc."
                               value="<?= htmlspecialchars($item['label']) ?>">
                        <small class="text-muted">The text displayed in the navigation menu</small>
                    </div>

                    <div class="form-group">
                        <label for="url">URL <span style="color: red;">*</span></label>
                        <input type="text" 
                               id="url" 
                               name="url" 
                               class="form-control" 
                               required
                               placeholder="/page/about-us, /contact, https://example.com"
                               value="<?= htmlspecialchars($item['url']) ?>">
                        <small class="text-muted">Internal path (e.g., /page/about) or full URL (https://...)</small>
                    </div>

                    <div class="form-group">
                        <label for="parent_id">Parent Item</label>
                        <select id="parent_id" name="parent_id" class="form-control">
                            <option value="">None (Top Level)</option>
                            <?php foreach ($parentItems as $parent): ?>
                                <option value="<?= $parent['id'] ?>" <?= $item['parent_id'] == $parent['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($parent['label']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Select a parent to create a dropdown menu item</small>
                    </div>

                    <div class="form-group">
                        <label for="icon">Icon (Font Awesome)</label>
                        <input type="text" 
                               id="icon" 
                               name="icon" 
                               class="form-control" 
                               placeholder="fas fa-home"
                               value="<?= htmlspecialchars($item['icon'] ?? '') ?>">
                        <small class="text-muted">Optional. Use Font Awesome classes (e.g., "fas fa-home"). <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a></small>
                    </div>

                    <div class="form-group">
                        <label for="target">Link Target</label>
                        <select id="target" name="target" class="form-control">
                            <option value="_self" <?= $item['target'] === '_self' ? 'selected' : '' ?>>Same Tab (_self)</option>
                            <option value="_blank" <?= $item['target'] === '_blank' ? 'selected' : '' ?>>New Tab (_blank)</option>
                        </select>
                        <small class="text-muted">Whether to open the link in the same tab or new tab</small>
                    </div>

                    <div class="form-group">
                        <label for="visible_to">Visibility</label>
                        <select id="visible_to" name="visible_to" class="form-control">
                            <option value="all" <?= $item['visible_to'] === 'all' ? 'selected' : '' ?>>Everyone</option>
                            <option value="guests" <?= $item['visible_to'] === 'guests' ? 'selected' : '' ?>>Guests Only (Not logged in)</option>
                            <option value="users" <?= $item['visible_to'] === 'users' ? 'selected' : '' ?>>Logged In Users</option>
                            <option value="admin" <?= $item['visible_to'] === 'admin' ? 'selected' : '' ?>>Admins Only</option>
                        </select>
                        <small class="text-muted">Control who can see this menu item</small>
                    </div>

                    <div class="form-group">
                        <label for="order">Order</label>
                        <input type="number" 
                               id="order" 
                               name="order" 
                               class="form-control" 
                               min="0"
                               value="<?= intval($item['order']) ?>">
                        <small class="text-muted">Lower numbers appear first (0 = first position)</small>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active" <?= $item['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $item['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <small class="text-muted">Only active items will be displayed on the site</small>
                    </div>

                    <div class="form-actions" style="display: flex; gap: 10px; margin-top: 30px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Navigation Item
                        </button>
                        <a href="<?= url('admin/navigation') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>

            <div class="glass-card" style="margin-top: 20px;">
                <h3 style="margin-bottom: 15px;">Item Information</h3>
                <div style="display: grid; gap: 10px;">
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                        <span style="font-weight: 600;">Created:</span>
                        <span><?= date('M d, Y H:i', strtotime($item['created_at'])) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                        <span style="font-weight: 600;">Last Updated:</span>
                        <span><?= date('M d, Y H:i', strtotime($item['updated_at'])) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                        <span style="font-weight: 600;">Item ID:</span>
                        <span><?= $item['id'] ?></span>
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
