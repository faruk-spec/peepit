<?php ob_start(); ?>

<div class="mb-30">
    <a href="<?= url('admin/templates') ?>" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <h2 class="mb-20"><i class="fas fa-plus-circle"></i> Add New Label Template</h2>

    <form method="POST" action="<?= url('admin/templates/store') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name" class="required">Template Name</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   class="form-control" 
                   value="<?= old('name') ?>"
                   placeholder="e.g., Corporate Logo Template"
                   required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" 
                   name="category" 
                   id="category" 
                   class="form-control" 
                   value="<?= old('category') ?>"
                   placeholder="e.g., Business, Sports, Event">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" 
                      id="description" 
                      class="form-control" 
                      rows="3"
                      placeholder="Describe this template..."><?= old('description') ?></textarea>
        </div>

        <div class="form-group">
            <label for="image">Template Preview Image</label>
            <input type="file" 
                   name="image" 
                   id="image" 
                   class="form-control" 
                   accept="image/png,image/jpeg,image/jpg">
            <small class="form-text">Recommended: 800x600px. Max 2MB. JPG or PNG.</small>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Create Template
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
$page_title = 'Add Label Template';
$current_page = 'bottles';
include __DIR__ . '/../../layouts/admin.php';
?>
