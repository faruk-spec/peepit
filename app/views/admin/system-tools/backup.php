<?php $this->layout('layouts/admin', ['title' => $title ?? 'Backup Management']); ?>

<div class="page-header">
    <div>
        <h1>🗄️ Backup Management</h1>
        <p>Create and manage database backups</p>
    </div>
    <div>
        <a href="/admin/system-tools" class="btn btn-secondary">← Back to Tools</a>
        <form method="POST" action="/admin/system-tools/backup/create" style="display: inline;" onsubmit="return confirm('This will create a new database backup. Continue?');">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <button type="submit" class="btn btn-primary">Create New Backup</button>
        </form>
    </div>
</div>

<!-- Backup Information -->
<div class="glass-card" style="margin-bottom: 30px;">
    <h2>Backup Information</h2>
    <div style="background: #EFF6FF; border-left: 4px solid #0EA5E9; padding: 20px; border-radius: 8px; margin-top: 20px;">
        <h3 style="margin-top: 0; color: #0EA5E9;">📋 About Backups</h3>
        <ul style="margin: 10px 0; padding-left: 20px; color: #475569;">
            <li>Backups include all database tables and data</li>
            <li>Files are stored in SQL format and compressed</li>
            <li>Download backups before deleting them</li>
            <li>Regular backups are recommended (daily or weekly)</li>
            <li>Keep at least 3 recent backups for recovery</li>
        </ul>
    </div>
</div>

<!-- Backups Table -->
<div class="glass-card">
    <h2>Available Backups</h2>
    <?php if (empty($backups)): ?>
        <div style="text-align: center; padding: 60px 20px; color: #64748B;">
            <div style="font-size: 4rem; margin-bottom: 20px;">🗄️</div>
            <h3>No Backups Found</h3>
            <p>Create your first backup using the button above.</p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Size</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($backups as $backup): ?>
                        <tr>
                            <td style="font-family: monospace; font-size: 0.875rem;"><?= htmlspecialchars($backup['filename']) ?></td>
                            <td><?= htmlspecialchars($backup['size']) ?></td>
                            <td><?= htmlspecialchars($backup['created']) ?></td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="/admin/system-tools/backup/download/<?= urlencode($backup['filename']) ?>" class="btn btn-sm btn-primary">
                                        ⬇️ Download
                                    </a>
                                    <form method="POST" action="/admin/system-tools/backup/delete" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this backup?');">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="filename" value="<?= htmlspecialchars($backup['filename']) ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️ Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 20px; padding: 15px; background: #FEF3C7; border: 1px solid #FCD34D; border-radius: 8px; font-size: 0.875rem;">
            <strong>⚠️ Important:</strong> Store backups in a secure, off-site location. 
            Local backups may be lost if the server fails. Download and store backups regularly.
        </div>
    <?php endif; ?>
</div>

<!-- Backup Best Practices -->
<div class="glass-card" style="margin-top: 30px;">
    <h2>Backup Best Practices</h2>
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
        <div style="padding: 20px; background: linear-gradient(135deg, #10B981, #06B6D4); border-radius: 12px; color: white;">
            <div style="font-size: 2rem; margin-bottom: 10px;">📅</div>
            <h3 style="margin: 0 0 8px 0;">Regular Schedule</h3>
            <p style="margin: 0; opacity: 0.9; font-size: 0.875rem;">Create backups daily or weekly based on your update frequency</p>
        </div>
        <div style="padding: 20px; background: linear-gradient(135deg, #0EA5E9, #8B5CF6); border-radius: 12px; color: white;">
            <div style="font-size: 2rem; margin-bottom: 10px;">🔒</div>
            <h3 style="margin: 0 0 8px 0;">Secure Storage</h3>
            <p style="margin: 0; opacity: 0.9; font-size: 0.875rem;">Store backups off-site in encrypted cloud storage</p>
        </div>
        <div style="padding: 20px; background: linear-gradient(135deg, #F59E0B, #EF4444); border-radius: 12px; color: white;">
            <div style="font-size: 2rem; margin-bottom: 10px;">✅</div>
            <h3 style="margin: 0 0 8px 0;">Test Restores</h3>
            <p style="margin: 0; opacity: 0.9; font-size: 0.875rem;">Periodically test backup restoration to ensure data integrity</p>
        </div>
        <div style="padding: 20px; background: linear-gradient(135deg, #EC4899, #8B5CF6); border-radius: 12px; color: white;">
            <div style="font-size: 2rem; margin-bottom: 10px;">📦</div>
            <h3 style="margin: 0 0 8px 0;">Version Control</h3>
            <p style="margin: 0; opacity: 0.9; font-size: 0.875rem;">Keep multiple backup versions for different restore points</p>
        </div>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    gap: 20px;
}

.page-header > div:last-child {
    display: flex;
    gap: 10px;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
    }
    
    .page-header > div:last-child {
        width: 100%;
        flex-direction: column;
    }
}
</style>
