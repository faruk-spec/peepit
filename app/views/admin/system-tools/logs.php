<?php $this->layout('layouts/admin', ['title' => $title ?? 'System Logs']); ?>

<div class="page-header">
    <div>
        <h1>📝 System Logs</h1>
        <p>View and manage system log files</p>
    </div>
    <div>
        <a href="/admin/system-tools" class="btn btn-secondary">← Back to Tools</a>
    </div>
</div>

<!-- Log File Selector -->
<div class="glass-card" style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div style="flex: 1; min-width: 250px;">
            <label for="logFile" style="display: block; margin-bottom: 8px; font-weight: 500;">Select Log File:</label>
            <select id="logFile" onchange="window.location.href='/admin/system-tools/logs?file=' + this.value" class="form-control">
                <?php foreach ($log_files as $file): ?>
                    <option value="<?= htmlspecialchars($file['name']) ?>" <?= $file['name'] === $current_file ? 'selected' : '' ?>>
                        <?= htmlspecialchars($file['name']) ?> (<?= htmlspecialchars($file['size']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <form method="POST" action="/admin/system-tools/logs/clear" style="display: inline;" onsubmit="return confirm('Are you sure you want to clear this log file?');">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="log_file" value="<?= htmlspecialchars($current_file) ?>">
            <button type="submit" class="btn btn-danger">Clear This Log</button>
        </form>
    </div>
</div>

<!-- Log Content -->
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2 style="margin: 0;">Log Content (Last 1000 lines)</h2>
        <button onclick="document.getElementById('logContent').select(); document.execCommand('copy');" class="btn btn-sm btn-secondary">
            Copy to Clipboard
        </button>
    </div>
    
    <?php if (empty($log_content) || trim($log_content) === '' || $log_content === 'Log file not found.'): ?>
        <div style="text-align: center; padding: 60px 20px; color: #64748B;">
            <div style="font-size: 4rem; margin-bottom: 20px;">📝</div>
            <h3>No Log Entries</h3>
            <p><?= $log_content === 'Log file not found.' ? 'Log file not found.' : 'This log file is empty.' ?></p>
        </div>
    <?php else: ?>
        <textarea 
            id="logContent" 
            readonly 
            style="width: 100%; height: 600px; font-family: 'Courier New', monospace; font-size: 12px; padding: 15px; background: #0F172A; color: #10B981; border: 1px solid #334155; border-radius: 8px; resize: vertical;"
        ><?= htmlspecialchars($log_content) ?></textarea>
        
        <div style="margin-top: 15px; padding: 15px; background: #FEF3C7; border: 1px solid #FCD34D; border-radius: 8px; font-size: 0.875rem;">
            <strong>💡 Tip:</strong> Log entries are displayed in reverse chronological order (newest first). 
            Use Ctrl+F (Cmd+F on Mac) to search within the log content.
        </div>
    <?php endif; ?>
</div>

<!-- Log Statistics -->
<?php if (!empty($log_files)): ?>
<div class="glass-card" style="margin-top: 30px;">
    <h2>Available Log Files</h2>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Size</th>
                    <th>Last Modified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($log_files as $file): ?>
                    <tr>
                        <td style="font-family: monospace; font-size: 0.875rem;">
                            <?php if ($file['name'] === $current_file): ?>
                                <strong><?= htmlspecialchars($file['name']) ?></strong>
                                <span class="badge badge-primary" style="margin-left: 8px;">Current</span>
                            <?php else: ?>
                                <?= htmlspecialchars($file['name']) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($file['size']) ?></td>
                        <td><?= htmlspecialchars($file['modified']) ?></td>
                        <td>
                            <a href="/admin/system-tools/logs?file=<?= urlencode($file['name']) ?>" class="btn btn-sm btn-secondary">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    gap: 20px;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
    }
}
</style>

<script>
// Auto-scroll to bottom of log content
document.addEventListener('DOMContentLoaded', function() {
    const logContent = document.getElementById('logContent');
    if (logContent) {
        logContent.scrollTop = logContent.scrollHeight;
    }
});
</script>
