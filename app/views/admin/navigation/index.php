<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Management - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
    <?php require_once __DIR__ . '/../../layouts/admin.php'; ?>

    <div class="admin-content">
        <div class="container">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div>
                    <h1>🧭 Navigation Management</h1>
                    <p class="text-muted">Manage site navigation menu items</p>
                </div>
                <a href="<?= url('admin/navigation/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Navigation Item
                </a>
            </div>

            <?php if (has_flash()): ?>
                <div class="alert alert-<?= flash_type() ?>">
                    <?= get_flash() ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($items)): ?>
                <div class="glass-card">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Label</th>
                                    <th>URL</th>
                                    <th>Parent</th>
                                    <th>Visibility</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr style="<?= $item['parent_id'] ? 'background: rgba(14, 165, 233, 0.05);' : '' ?>">
                                        <td>
                                            <span class="badge badge-secondary"><?= intval($item['order']) ?></span>
                                        </td>
                                        <td>
                                            <?php if ($item['parent_id']): ?>
                                                <span style="margin-left: 20px;">└─</span>
                                            <?php endif; ?>
                                            <?php if ($item['icon']): ?>
                                                <i class="<?= htmlspecialchars($item['icon']) ?>"></i>
                                            <?php endif; ?>
                                            <strong><?= htmlspecialchars($item['label']) ?></strong>
                                        </td>
                                        <td>
                                            <code><?= htmlspecialchars($item['url']) ?></code>
                                            <?php if ($item['target'] === '_blank'): ?>
                                                <i class="fas fa-external-link-alt" title="Opens in new tab"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($item['parent_label']): ?>
                                                <span class="badge badge-info"><?= htmlspecialchars($item['parent_label']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $visibilityLabels = [
                                                'all' => 'Everyone',
                                                'guests' => 'Guests Only',
                                                'users' => 'Logged In',
                                                'admin' => 'Admins Only'
                                            ];
                                            $badgeColors = [
                                                'all' => 'secondary',
                                                'guests' => 'warning',
                                                'users' => 'info',
                                                'admin' => 'danger'
                                            ];
                                            $visibility = $item['visible_to'];
                                            ?>
                                            <span class="badge badge-<?= $badgeColors[$visibility] ?? 'secondary' ?>">
                                                <?= $visibilityLabels[$visibility] ?? ucfirst($visibility) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($item['status'] === 'active'): ?>
                                                <span class="badge badge-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 10px;">
                                                <a href="<?= url('admin/navigation/edit/' . $item['id']) ?>" 
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?= url('admin/navigation/delete') ?>" 
                                                      method="POST" 
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Are you sure you want to delete this navigation item?');">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
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

                <div class="glass-card" style="margin-top: 20px; padding: 20px;">
                    <h3 style="margin-bottom: 15px;"><i class="fas fa-info-circle"></i> Navigation Tips</h3>
                    <ul style="color: #64748b; line-height: 1.8;">
                        <li><strong>Order:</strong> Lower numbers appear first in the menu</li>
                        <li><strong>Parent Items:</strong> Create dropdown menus by setting a parent item</li>
                        <li><strong>Visibility:</strong> Control who can see each menu item based on login status</li>
                        <li><strong>Icons:</strong> Use Font Awesome classes (e.g., "fas fa-home")</li>
                        <li><strong>Status:</strong> Inactive items won't be displayed on the site</li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="glass-card" style="padding: 60px; text-align: center;">
                    <div style="font-size: 4rem; margin-bottom: 20px;">🧭</div>
                    <h3 style="color: #64748b; margin-bottom: 10px;">No Navigation Items Yet</h3>
                    <p style="color: #94a3b8; margin-bottom: 20px;">Create your first navigation item to get started.</p>
                    <a href="<?= url('admin/navigation/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Navigation Item
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 15px;
            }
            
            .page-header .btn {
                width: 100%;
            }
            
            .table {
                font-size: 0.875rem;
            }
            
            .table td, .table th {
                padding: 10px 8px;
            }
            
            .table td:nth-child(3),
            .table th:nth-child(3),
            .table td:nth-child(4),
            .table th:nth-child(4) {
                display: none;
            }
        }
    </style>
</body>
</html>
