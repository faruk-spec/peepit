<?php ob_start(); ?>

<div class="mb-30">
    <h2 style="margin: 0 0 5px;">User Management</h2>
    <p style="margin: 0; color: var(--text-light);">Manage user accounts and roles</p>
</div>

<div class="card admin-table">
    <?php if (!empty($users)): ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><strong><?= escape($user['name']) ?></strong></td>
                        <td><?= escape($user['email']) ?></td>
                        <td><?= escape($user['phone'] ?? '-') ?></td>
                        <td>
                            <span class="badge badge-primary">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-<?= $user['status'] === 'active' ? 'completed' : 'cancelled' ?>">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="<?= url('admin/users/edit/' . $user['id']) ?>" 
                                   class="btn btn-primary" 
                                   style="padding: 6px 12px; font-size: 14px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if (has_role('superadmin') && $user['id'] != user_id()): ?>
                                    <form method="POST" 
                                          action="<?= url('admin/users/delete/' . $user['id']) ?>" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" 
                                                class="btn" 
                                                style="padding: 6px 12px; font-size: 14px; background: var(--error); color: white;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="text-center" style="padding: 60px 20px;">
            <div style="font-size: 64px; color: var(--text-light); margin-bottom: 20px;">
                <i class="fas fa-users"></i>
            </div>
            <h3>No Users Found</h3>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
$page_title = 'User Management';
$current_page = 'users';
include __DIR__ . '/../../layouts/admin.php';
?>
