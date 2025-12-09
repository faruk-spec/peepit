<?php ob_start(); ?>

<div class="mb-30">
    <a href="<?= url('admin/templates') ?>" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <h2 class="mb-20"><i class="fas fa-edit"></i> Edit Label Template</h2>

    <form method="POST" action="<?= url('admin/templates/update/' . $template['id']) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name" class="required">Template Name</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   class="form-control" 
                   value="<?= escape($template['name']) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" 
                   name="category" 
                   id="category" 
                   class="form-control" 
                   value="<?= escape($template['category'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" 
                      id="description" 
                      class="form-control" 
                      rows="3"><?= escape($template['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="image">Template Preview Image</label>
            <?php if ($template['image']): ?>
                <div class="mb-15">
                    <img src="<?= url('uploads/templates/' . escape($template['image'])) ?>" 
                         alt="Current image"
                         style="max-width: 300px; border-radius: 8px; border: 2px solid var(--border);">
                </div>
            <?php endif; ?>
            <input type="file" 
                   name="image" 
                   id="image" 
                   class="form-control" 
                   accept="image/png,image/jpeg,image/jpg">
            <small class="form-text">Leave empty to keep current image.</small>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="active" <?= $template['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $template['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Update Template
            </button>
            <a href="<?= url('admin/templates') ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<style>
    .required::after {
        content: '*';
        color: var(--error);
        margin-left: 5px;
    }

    .form-text {
        display: block;
        margin-top: 5px;
        font-size: 13px;
        color: var(--text-light);
    }
</style>

<?php
$content = ob_get_clean();
$page_title = 'Edit Label Template';
$current_page = 'bottles';
include __DIR__ . '/../../layouts/admin.php';
?>
