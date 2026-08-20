<?php ob_start(); ?>

<div class="mb-30">
    <a href="<?= url('admin/colors') ?>" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="card" style="max-width: 600px;">
    <h2 class="mb-20"><i class="fas fa-plus-circle"></i> Add New Color Preset</h2>

    <form method="POST" action="<?= url('admin/colors/store') ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name" class="required">Color Name</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   class="form-control" 
                   value="<?= old('name') ?>"
                   placeholder="e.g., Ocean Blue"
                   required>
        </div>

        <div class="form-group">
            <label for="hex_code" class="required">Hex Code</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="color" 
                       id="colorPicker" 
                       value="<?= old('hex_code', '#0EA5E9') ?>"
                       style="width: 60px; height: 50px; border: 2px solid var(--border); border-radius: 8px; cursor: pointer;">
                <input type="text" 
                       name="hex_code" 
                       id="hex_code" 
                       class="form-control" 
                       value="<?= old('hex_code', '#0EA5E9') ?>"
                       placeholder="#0EA5E9"
                       pattern="^#[0-9A-Fa-f]{6}$"
                       required>
            </div>
            <small class="form-text">Format: #RRGGBB (e.g., #0EA5E9)</small>
        </div>

        <div class="form-group">
            <label>Preview</label>
            <div id="colorPreview" 
                 style="width: 100%; height: 80px; border-radius: 12px; background: <?= old('hex_code', '#0EA5E9') ?>; border: 2px solid var(--border);"></div>
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
                <i class="fas fa-save"></i> Create Color
            </button>
            <a href="<?= url('admin/colors') ?>" class="btn btn-outline">Cancel</a>
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

<script>
    const colorPicker = document.getElementById('colorPicker');
    const hexCode = document.getElementById('hex_code');
    const colorPreview = document.getElementById('colorPreview');

    colorPicker.addEventListener('input', function() {
        hexCode.value = this.value.toUpperCase();
        colorPreview.style.background = this.value;
    });

    hexCode.addEventListener('input', function() {
        const value = this.value;
        if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
            colorPicker.value = value;
            colorPreview.style.background = value;
        }
    });
</script>

<?php
$content = ob_get_clean();
$page_title = 'Add Color Preset';
$current_page = 'bottles';
include __DIR__ . '/../../layouts/admin.php';
?>
