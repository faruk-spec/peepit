<?php ob_start(); ?>

<div class="mb-30" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="margin: 0 0 5px;">Bottle Models</h2>
        <p style="margin: 0; color: var(--text-light);">Manage bottle model catalog</p>
    </div>
    <a href="<?= url('admin/bottles/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Add New Model
    </a>
</div>

<?php if (!empty($bottles)): ?>
    <div class="card admin-table">
        <table>
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
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            <?php else: ?>
                                <div style="width: 60px; height: 60px; background: var(--light); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-wine-bottle" style="font-size: 24px; color: var(--text-light);"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= escape($bottle['name']) ?></strong></td>
                        <td><?= escape(substr($bottle['description'] ?? '', 0, 50)) ?><?= strlen($bottle['description'] ?? '') > 50 ? '...' : '' ?></td>
                        <td>
                            <span class="status-badge status-<?= $bottle['status'] === 'active' ? 'completed' : 'cancelled' ?>">
                                <?= ucfirst($bottle['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($bottle['created_at'])) ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="<?= url('admin/bottles/edit/' . $bottle['id']) ?>" 
                                   class="btn btn-primary" 
                                   style="padding: 6px 12px; font-size: 14px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" 
                                      action="<?= url('admin/bottles/delete/' . $bottle['id']) ?>" 
                                      style="display: inline;"
                                      onsubmit="return confirm('Are you sure you want to delete this bottle model?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" 
                                            class="btn" 
                                            style="padding: 6px 12px; font-size: 14px; background: var(--error); color: white;">
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
<?php else: ?>
    <div class="card text-center" style="padding: 60px 20px;">
        <div style="font-size: 64px; color: var(--text-light); margin-bottom: 20px;">
            <i class="fas fa-wine-bottle"></i>
        </div>
        <h3>No Bottle Models Yet</h3>
        <p style="color: var(--text-light); margin-bottom: 20px;">Start by adding your first bottle model</p>
        <a href="<?= url('admin/bottles/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add First Model
        </a>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$page_title = 'Bottle Models';
$current_page = 'bottles';
include __DIR__ . '/../../layouts/admin.php';
?>
