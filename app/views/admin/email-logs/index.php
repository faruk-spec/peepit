<?php ob_start(); ?>

<div class="mb-30" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="margin: 0 0 5px;">Email Logs</h2>
        <p style="margin: 0; color: var(--text-light);">View sent email history and delivery status</p>
    </div>
    <?php if (has_role('superadmin')): ?>
        <form method="POST" action="<?= url('admin/email-logs/clear') ?>" style="display: inline;">
            <?= csrf_field() ?>
            <button type="submit" 
                    class="btn btn-outline" 
                    onclick="return confirm('Clear logs older than 30 days?');">
                <i class="fas fa-trash"></i> Clear Old Logs
            </button>
        </form>
    <?php endif; ?>
</div>

<div class="card admin-table">
    <?php if (!empty($logs)): ?>
        <table>
            <thead>
                <tr>
                    <th>To</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Sent At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= escape($log['to_email']) ?></td>
                        <td><strong><?= escape($log['subject']) ?></strong></td>
                        <td>
                            <span class="status-badge status-<?= $log['status'] === 'sent' ? 'completed' : ($log['status'] === 'failed' ? 'cancelled' : 'pending') ?>">
                                <?= ucfirst($log['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y, g:i A', strtotime($log['created_at'])) ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="<?= url('admin/email-logs/view/' . $log['id']) ?>" 
                                   class="btn btn-primary" 
                                   style="padding: 6px 12px; font-size: 14px;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if (has_role('superadmin')): ?>
                                    <form method="POST" 
                                          action="<?= url('admin/email-logs/delete/' . $log['id']) ?>" 
                                          style="display: inline;">
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
                <i class="fas fa-envelope"></i>
            </div>
            <h3>No Email Logs</h3>
            <p style="color: var(--text-light);">Email logs will appear here as emails are sent</p>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
$page_title = 'Email Logs';
$current_page = 'email-logs';
include __DIR__ . '/../../layouts/admin.php';
?>
