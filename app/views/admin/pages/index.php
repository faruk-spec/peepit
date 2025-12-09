<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pages Management - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
    <?php require_once __DIR__ . '/../../layouts/admin.php'; ?>

    <div class="admin-content">
        <div class="container">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div>
                    <h1>📄 Pages Management</h1>
                    <p class="text-muted">Create and manage custom pages</p>
                </div>
                <a href="<?= url('admin/pages/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Page
                </a>
            </div>

            <?php if (has_flash()): ?>
                <div class="alert alert-<?= flash_type() ?>">
                    <?= get_flash() ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($pages)): ?>
                <div class="glass-card">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pages as $page): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($page['title']) ?></strong>
                                        </td>
                                        <td>
                                            <code>/page/<?= htmlspecialchars($page['slug']) ?></code>
                                        </td>
                                        <td>
                                            <?php if ($page['status'] === 'published'): ?>
                                                <span class="badge badge-success">Published</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($page['created_by_name'] ?? 'Unknown') ?></td>
                                        <td><?= date('M d, Y H:i', strtotime($page['updated_at'])) ?></td>
                                        <td>
                                            <div style="display: flex; gap: 10px;">
                                                <?php if ($page['status'] === 'published'): ?>
                                                    <a href="<?= url('page/' . $page['slug']) ?>" 
                                                       class="btn btn-sm btn-secondary" 
                                                       target="_blank"
                                                       title="View Page">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?= url('admin/pages/edit/' . $page['id']) ?>" 
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?= url('admin/pages/delete') ?>" 
                                                      method="POST" 
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Are you sure you want to delete this page?');">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                                    <input type="hidden" name="id" value="<?= $page['id'] ?>">
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
            <?php else: ?>
                <div class="glass-card" style="padding: 60px; text-align: center;">
                    <div style="font-size: 4rem; margin-bottom: 20px;">📄</div>
                    <h3 style="color: #64748b; margin-bottom: 10px;">No Pages Yet</h3>
                    <p style="color: #94a3b8; margin-bottom: 20px;">Create your first custom page to get started.</p>
                    <a href="<?= url('admin/pages/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Page
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
            
            .table td:nth-child(4),
            .table th:nth-child(4) {
                display: none;
            }
        }
    </style>
</body>
</html>
