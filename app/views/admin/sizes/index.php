<?php ob_start(); ?>

<div class="mb-30" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="margin: 0 0 5px;">Bottle Sizes</h2>
        <p style="margin: 0; color: var(--text-light);">Manage available bottle capacities</p>
    </div>
    <a href="<?= url('admin/sizes/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Add New Size
    </a>
</div>

<?php if (!empty($sizes)): ?>
    <div class="card admin-table">
        <table>
            <thead>
                <tr>
                    <th>Size</th>
                    <th>Capacity (ml)</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sizes as $size): ?>
                    <tr>
                        <td><strong><?= escape($size['size']) ?></strong></td>
                        <td><?= number_format($size['capacity_ml']) ?> ml</td>
                        <td>
                            <span class="status-badge status-<?= $size['status'] === 'active' ? 'completed' : 'cancelled' ?>">
                                <?= ucfirst($size['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($size['created_at'])) ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="<?= url('admin/sizes/edit/' . $size['id']) ?>" 
                                   class="btn btn-primary" 
                                   style="padding: 6px 12px; font-size: 14px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" 
                                      action="<?= url('admin/sizes/delete/' . $size['id']) ?>" 
                                      style="display: inline;"
                                      onsubmit="return confirm('Are you sure? This may affect existing orders.');">
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
            <i class="fas fa-ruler-combined"></i>
        </div>
        <h3>No Bottle Sizes Yet</h3>
        <p style="color: var(--text-light); margin-bottom: 20px;">Add bottle size options for customers</p>
        <a href="<?= url('admin/sizes/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add First Size
        </a>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$page_title = 'Bottle Sizes';
$current_page = 'bottles';
include __DIR__ . '/../../layouts/admin.php';
?>
