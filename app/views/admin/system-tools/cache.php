<?php $this->layout('layouts/admin', ['title' => $title ?? 'Cache Management']); ?>

<div class="page-header">
    <div>
        <h1>🗑️ Cache Management</h1>
        <p>View and manage system cache files</p>
    </div>
    <div>
        <a href="/admin/system-tools" class="btn btn-secondary">← Back to Tools</a>
        <form method="POST" action="/admin/system-tools/cache/clear" style="display: inline;" onsubmit="return confirm('Are you sure you want to clear all cache?');">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <button type="submit" class="btn btn-danger">Clear All Cache</button>
        </form>
    </div>
</div>

<!-- Cache Statistics -->
<div class="glass-card" style="margin-bottom: 30px;">
    <h2>Cache Statistics</h2>
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
        <div style="text-align: center; padding: 20px; background: linear-gradient(135deg, #06B6D4, #0EA5E9); border-radius: 12px; color: white;">
            <div style="font-size: 2rem; font-weight: 600;"><?= htmlspecialchars($cache_size) ?></div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Total Cache Size</div>
        </div>
        <div style="text-align: center; padding: 20px; background: linear-gradient(135deg, #10B981, #06B6D4); border-radius: 12px; color: white;">
            <div style="font-size: 2rem; font-weight: 600;"><?= count($cache_files) ?></div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Cache Files</div>
        </div>
    </div>
</div>

<!-- Cache Files Table -->
<div class="glass-card">
    <h2>Cache Files</h2>
    <?php if (empty($cache_files)): ?>
        <div style="text-align: center; padding: 60px 20px; color: #64748B;">
            <div style="font-size: 4rem; margin-bottom: 20px;">🗑️</div>
            <h3>No Cache Files</h3>
            <p>The cache is currently empty.</p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Size</th>
                        <th>Last Modified</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cache_files as $file): ?>
                        <tr>
                            <td style="font-family: monospace; font-size: 0.875rem;"><?= htmlspecialchars($file['name']) ?></td>
                            <td><?= htmlspecialchars($file['size']) ?></td>
                            <td><?= htmlspecialchars($file['modified']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
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
