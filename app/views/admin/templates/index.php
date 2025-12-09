<?php ob_start(); ?>

<div class="mb-30" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="margin: 0 0 5px;">Label Templates</h2>
        <p style="margin: 0; color: var(--text-light);">Manage label design templates for customers</p>
    </div>
    <a href="<?= url('admin/templates/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Add New Template
    </a>
</div>

<?php if (!empty($templates)): ?>
    <div class="grid grid-3" style="gap: 20px;">
        <?php foreach ($templates as $template): ?>
            <div class="card">
                <?php if ($template['image']): ?>
                    <img src="<?= url('uploads/templates/' . escape($template['image'])) ?>" 
                         alt="<?= escape($template['name']) ?>"
                         style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px 8px 0 0; margin: -20px -20px 15px;">
                <?php else: ?>
                    <div style="width: calc(100% + 40px); height: 200px; margin: -20px -20px 15px; background: var(--light); display: flex; align-items: center; justify-content: center; border-radius: 8px 8px 0 0;">
                        <i class="fas fa-image" style="font-size: 64px; color: var(--text-light);"></i>
                    </div>
                <?php endif; ?>
                
                <h3 style="margin: 0 0 5px;"><?= escape($template['name']) ?></h3>
                <?php if ($template['category']): ?>
                    <span class="badge badge-primary" style="margin-bottom: 10px;"><?= escape($template['category']) ?></span>
                <?php endif; ?>
                
                <?php if ($template['description']): ?>
                    <p style="font-size: 14px; color: var(--text-light); margin-bottom: 15px;">
                        <?= escape(substr($template['description'], 0, 80)) ?><?= strlen($template['description']) > 80 ? '...' : '' ?>
                    </p>
                <?php endif; ?>
                
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="status-badge status-<?= $template['status'] === 'active' ? 'completed' : 'cancelled' ?>">
                        <?= ucfirst($template['status']) ?>
                    </span>
                    <div style="display: flex; gap: 8px;">
                        <a href="<?= url('admin/templates/edit/' . $template['id']) ?>" 
                           class="btn btn-primary" 
                           style="padding: 6px 12px; font-size: 14px;">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" 
                              action="<?= url('admin/templates/delete/' . $template['id']) ?>" 
                              style="display: inline;"
                              onsubmit="return confirm('Are you sure?');">
                            <?= csrf_field() ?>
                            <button type="submit" 
                                    class="btn" 
                                    style="padding: 6px 12px; font-size: 14px; background: var(--error); color: white;">
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
            <i class="fas fa-tags"></i>
        </div>
        <h3>No Label Templates Yet</h3>
        <p style="color: var(--text-light); margin-bottom: 20px;">Create templates for customers to use on their bottles</p>
        <a href="<?= url('admin/templates/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add First Template
        </a>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$page_title = 'Label Templates';
$current_page = 'templates';
include __DIR__ . '/../../layouts/admin.php';
?>
