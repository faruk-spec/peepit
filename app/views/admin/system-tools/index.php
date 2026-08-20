<?php $this->layout('layouts/admin', ['title' => $title ?? 'System Tools']); ?>

<div class="page-header">
    <h1>🛠️ System Tools</h1>
    <p>Manage system cache, logs, backups, and monitor disk usage</p>
</div>

<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <style>
        @media (max-width: 768px) {
            .glass-card { padding: 15px; }
            .grid { gap: 15px !important; }
        }
        @media (max-width: 480px) {
            .grid { grid-template-columns: 1fr !important; }
        }
    </style>
    <!-- Cache Info Card -->
    <div class="glass-card">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #06B6D4, #0EA5E9); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 24px;">🗑️</span>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1rem; color: #64748B;">Cache Storage</h3>
                <p style="margin: 0; font-size: 1.5rem; font-weight: 600; color: #0F172A;"><?= htmlspecialchars($cache_info['size']) ?></p>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #E2E8F0;">
            <span style="color: #64748B; font-size: 0.875rem;"><?= intval($cache_info['files']) ?> files</span>
            <a href="/admin/system-tools/cache" class="btn btn-sm btn-primary">Manage Cache</a>
        </div>
    </div>

    <!-- Logs Info Card -->
    <div class="glass-card">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #F59E0B, #EF4444); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 24px;">📝</span>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1rem; color: #64748B;">System Logs</h3>
                <p style="margin: 0; font-size: 1.5rem; font-weight: 600; color: #0F172A;"><?= htmlspecialchars($log_info['size']) ?></p>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #E2E8F0;">
            <span style="color: #64748B; font-size: 0.875rem;"><?= intval($log_info['files']) ?> files</span>
            <a href="/admin/system-tools/logs" class="btn btn-sm btn-primary">View Logs</a>
        </div>
    </div>

    <!-- Backup Info Card -->
    <div class="glass-card">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #10B981, #06B6D4); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 24px;">🗄️</span>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1rem; color: #64748B;">Backups</h3>
                <p style="margin: 0; font-size: 1.5rem; font-weight: 600; color: #0F172A;"><?= htmlspecialchars($backup_info['size']) ?></p>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #E2E8F0;">
            <span style="color: #64748B; font-size: 0.875rem;"><?= intval($backup_info['files']) ?> backups</span>
            <a href="/admin/system-tools/backup" class="btn btn-sm btn-primary">Manage Backups</a>
        </div>
    </div>

    <!-- Disk Usage Card -->
    <div class="glass-card">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #8B5CF6, #EC4899); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 24px;">💾</span>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1rem; color: #64748B;">Disk Usage</h3>
                <p style="margin: 0; font-size: 1.5rem; font-weight: 600; color: #0F172A;"><?= htmlspecialchars($disk_info['percent']) ?>%</p>
            </div>
        </div>
        <div style="padding-top: 15px; border-top: 1px solid #E2E8F0;">
            <div style="background: #E2E8F0; height: 8px; border-radius: 4px; overflow: hidden;">
                <div style="background: linear-gradient(90deg, #06B6D4, #0EA5E9); height: 100%; width: <?= htmlspecialchars($disk_info['percent']) ?>%; transition: width 0.3s;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 8px; font-size: 0.75rem; color: #64748B;">
                <span>Used: <?= htmlspecialchars($disk_info['used']) ?></span>
                <span>Free: <?= htmlspecialchars($disk_info['free']) ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="glass-card">
    <h2 style="margin-bottom: 20px;">Quick Actions</h2>
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
        <style>
            @media (max-width: 768px) {
                .btn { min-height: 50px; font-size: 1rem; }
            }
            @media (max-width: 480px) {
                .grid { grid-template-columns: 1fr !important; }
            }
        </style>
        <a href="/admin/system-tools/cache" class="btn btn-secondary" style="display: flex; align-items: center; gap: 10px; justify-content: center;">
            <span>🗑️</span>
            <span>Manage Cache</span>
        </a>
        <a href="/admin/system-tools/logs" class="btn btn-secondary" style="display: flex; align-items: center; gap: 10px; justify-content: center;">
            <span>📝</span>
            <span>View Logs</span>
        </a>
        <a href="/admin/system-tools/backup" class="btn btn-secondary" style="display: flex; align-items: center; gap: 10px; justify-content: center;">
            <span>🗄️</span>
            <span>Create Backup</span>
        </a>
        <a href="/admin/settings" class="btn btn-secondary" style="display: flex; align-items: center; gap: 10px; justify-content: center;">
            <span>⚙️</span>
            <span>System Settings</span>
        </a>
    </div>
</div>

<!-- System Information -->
<div class="glass-card" style="margin-top: 30px;">
    <h2 style="margin-bottom: 20px;">System Information</h2>
    <div class="table-responsive" style="overflow-x: auto;">
        <table class="table">
            <style>
                @media (max-width: 768px) {
                    .table { font-size: 0.875rem; }
                    .table td { padding: 10px 8px; }
                }
            </style>
        <tbody>
            <tr>
                <td style="font-weight: 600;">PHP Version</td>
                <td><?= phpversion() ?></td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Server Software</td>
                <td><?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') ?></td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Max Upload Size</td>
                <td><?= ini_get('upload_max_filesize') ?></td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Memory Limit</td>
                <td><?= ini_get('memory_limit') ?></td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Execution Time Limit</td>
                <td><?= ini_get('max_execution_time') ?> seconds</td>
            </tr>
        </tbody>
    </table>
    </div>
</div>
