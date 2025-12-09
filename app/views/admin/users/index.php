<?php ob_start(); ?>

<div class="page-breadcrumb mb-4">
    <h4>User Management</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">All Users</h5>
        <div>
            <span class="badge bg-primary"><?= count($users) ?> Total</span>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($users)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
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
                                    <span class="badge bg-primary">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($user['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?= url('admin/users/edit/' . $user['id']) ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if (has_role('superadmin') && $user['id'] != user_id()): ?>
                                            <form method="POST" 
                                                  action="<?= url('admin/users/delete/' . $user['id']) ?>" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger">
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
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div style="font-size: 64px; color: #8897ad; margin-bottom: 20px;">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="mb-3">No Users Found</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$page_title = 'User Management';
$current_page = 'users';
include __DIR__ . '/../../layouts/admin.php';
?>
