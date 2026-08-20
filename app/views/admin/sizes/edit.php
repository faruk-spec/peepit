<?php ob_start(); ?>

<div class="mb-30">
    <a href="<?= url('admin/sizes') ?>" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="card" style="max-width: 600px;">
    <h2 class="mb-20"><i class="fas fa-edit"></i> Edit Bottle Size</h2>

    <form method="POST" action="<?= url('admin/sizes/update/' . $size['id']) ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="size" class="required">Size Name</label>
            <input type="text" 
                   name="size" 
                   id="size" 
                   class="form-control" 
                   value="<?= escape($size['size']) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="capacity_ml" class="required">Capacity (ml)</label>
            <input type="number" 
                   name="capacity_ml" 
                   id="capacity_ml" 
                   class="form-control" 
                   value="<?= escape($size['capacity_ml']) ?>"
                   min="1"
                   required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="active" <?= $size['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $size['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Update Size
            </button>
            <a href="<?= url('admin/sizes') ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<style>
    .required::after {
        content: '*';
        color: var(--error);
        margin-left: 5px;
    }
</style>

<?php
$content = ob_get_clean();
$page_title = 'Edit Bottle Size';
$current_page = 'bottles';
include __DIR__ . '/../../layouts/admin.php';
?>
