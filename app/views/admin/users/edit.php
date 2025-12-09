<?php ob_start(); ?>

<div class="mb-30">
    <a href="<?= url('admin/users') ?>" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
</div>

<div class="card" style="max-width: 700px;">
    <h2 class="mb-20"><i class="fas fa-user-edit"></i> Edit User</h2>

    <form method="POST" action="<?= url('admin/users/update/' . $user['id']) ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name" class="required">Full Name</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   class="form-control" 
                   value="<?= escape($user['name']) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="email" class="required">Email Address</label>
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="form-control" 
                   value="<?= escape($user['email']) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" 
                   name="phone" 
                   id="phone" 
                   class="form-control" 
                   value="<?= escape($user['phone'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="role" class="required">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
                <option value="sales" <?= $user['role'] === 'sales' ? 'selected' : '' ?>>Sales</option>
                <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
                <?php if (has_role('superadmin')): ?>
                    <option value="superadmin" <?= $user['role'] === 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
                <?php endif; ?>
            </select>
            <small class="form-text">
                <strong>Customer:</strong> Can place orders<br>
                <strong>Sales:</strong> Can view orders and dashboard<br>
                <strong>Manager:</strong> Full admin access except settings<br>
                <strong>Super Admin:</strong> Complete system access
            </small>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Update User
            </button>
            <a href="<?= url('admin/users') ?>" class="btn btn-outline">Cancel</a>
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
        line-height: 1.6;
    }
</style>

<?php
$content = ob_get_clean();
$page_title = 'Edit User';
$current_page = 'users';
include __DIR__ . '/../../layouts/admin.php';
?>
