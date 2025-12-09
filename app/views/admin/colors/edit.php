<?php ob_start(); ?>

<div class="mb-30">
    <a href="<?= url('admin/colors') ?>" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="card" style="max-width: 600px;">
    <h2 class="mb-20"><i class="fas fa-edit"></i> Edit Color Preset</h2>

    <form method="POST" action="<?= url('admin/colors/update/' . $color['id']) ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name" class="required">Color Name</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   class="form-control" 
                   value="<?= escape($color['name']) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="hex_code" class="required">Hex Code</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="color" 
                       id="colorPicker" 
                       value="<?= escape($color['hex_code']) ?>"
                       style="width: 60px; height: 50px; border: 2px solid var(--border); border-radius: 8px; cursor: pointer;">
                <input type="text" 
                       name="hex_code" 
                       id="hex_code" 
                       class="form-control" 
                       value="<?= escape($color['hex_code']) ?>"
                       pattern="^#[0-9A-Fa-f]{6}$"
                       required>
            </div>
        </div>

        <div class="form-group">
            <label>Preview</label>
            <div id="colorPreview" 
                 style="width: 100%; height: 80px; border-radius: 12px; background: <?= escape($color['hex_code']) ?>; border: 2px solid var(--border);"></div>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="active" <?= $color['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $color['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Update Color
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
$page_title = 'Edit Color Preset';
$current_page = 'bottles';
include __DIR__ . '/../../layouts/admin.php';
?>
