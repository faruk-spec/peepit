<?php ob_start(); ?>

<div class="mb-30" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="margin: 0 0 5px;">Color Presets</h2>
        <p style="margin: 0; color: var(--text-light);">Manage color options for bottles</p>
    </div>
    <a href="<?= url('admin/colors/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Add New Color
    </a>
</div>

<?php if (!empty($colors)): ?>
    <div class="grid grid-2" style="gap: 20px;">
        <?php foreach ($colors as $color): ?>
            <div class="card" style="border-left: 5px solid <?= escape($color['hex_code']) ?>;">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                            <div style="width: 60px; height: 60px; border-radius: 12px; background: <?= escape($color['hex_code']) ?>; border: 2px solid var(--border);"></div>
                            <div>
                                <h3 style="margin: 0 0 5px;"><?= escape($color['name']) ?></h3>
                                <div style="font-size: 13px; color: var(--text-light);">
                                    <div><?= escape($color['hex_code']) ?></div>
                                    <div><?= escape($color['rgb_code']) ?></div>
                                </div>
                            </div>
                        </div>
                        <span class="status-badge status-<?= $color['status'] === 'active' ? 'completed' : 'cancelled' ?>">
                            <?= ucfirst($color['status']) ?>
                        </span>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <a href="<?= url('admin/colors/edit/' . $color['id']) ?>" 
                           class="btn btn-primary" 
                           style="padding: 8px 15px; font-size: 14px;">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" 
                              action="<?= url('admin/colors/delete/' . $color['id']) ?>" 
                              style="display: inline;"
                              onsubmit="return confirm('Are you sure?');">
                            <?= csrf_field() ?>
                            <button type="submit" 
                                    class="btn" 
                                    style="padding: 8px 15px; font-size: 14px; background: var(--error); color: white;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="card text-center" style="padding: 60px 20px;">
        <div style="font-size: 64px; color: var(--text-light); margin-bottom: 20px;">
            <i class="fas fa-palette"></i>
        </div>
        <h3>No Color Presets Yet</h3>
        <p style="color: var(--text-light); margin-bottom: 20px;">Add color options for customers to choose from</p>
        <a href="<?= url('admin/colors/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add First Color
        </a>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$page_title = 'Color Presets';
$current_page = 'bottles';
include __DIR__ . '/../../layouts/admin.php';
?>
