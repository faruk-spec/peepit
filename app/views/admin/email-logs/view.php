<?php ob_start(); ?>

<div class="mb-30">
    <a href="<?= url('admin/email-logs') ?>" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Logs
    </a>
</div>

<div class="card">
    <h2 class="mb-20"><i class="fas fa-envelope-open"></i> Email Log Details</h2>

    <div style="display: grid; gap: 20px;">
        <div>
            <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 5px;">To:</label>
            <div><?= escape($log['to_email']) ?></div>
        </div>

        <div>
            <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 5px;">Subject:</label>
            <div><strong><?= escape($log['subject']) ?></strong></div>
        </div>

        <div>
            <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 5px;">Status:</label>
            <span class="status-badge status-<?= $log['status'] === 'sent' ? 'completed' : ($log['status'] === 'failed' ? 'cancelled' : 'pending') ?>">
                <?= ucfirst($log['status']) ?>
            </span>
        </div>

        <div>
            <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 5px;">Sent At:</label>
            <div><?= date('F d, Y \a\t g:i A', strtotime($log['created_at'])) ?></div>
        </div>

        <?php if (!empty($log['error_message'])): ?>
            <div>
                <label style="font-weight: 600; color: var(--error); display: block; margin-bottom: 5px;">Error Message:</label>
                <div style="padding: 15px; background: #FEE2E2; color: #991B1B; border-radius: 8px;">
                    <?= nl2br(escape($log['error_message'])) ?>
                </div>
            </div>
        <?php endif; ?>

        <div>
            <label style="font-weight: 600; color: var(--dark); display: block; margin-bottom: 10px;">Email Body:</label>
            <div style="padding: 20px; background: var(--light); border-radius: 8px; border: 1px solid var(--border);">
                <?= $log['body'] ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$page_title = 'Email Log Details';
$current_page = 'email-logs';
include __DIR__ . '/../../layouts/admin.php';
?>
