<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'Real-Time Visitors']); ?>

<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <div class="page-header" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 2rem; margin-bottom: 10px;">Real-Time Visitors</h1>
            <p style="color: #64748b;">Live visitor tracking • Auto-refresh every 5 seconds</p>
        </div>
        <button onclick="toggleAutoRefresh()" class="btn btn-secondary" style="padding: 10px 20px; background: rgba(14,165,233,0.1); color: #0EA5E9; border: none; border-radius: 8px; cursor: pointer;">
            <span id="refreshToggle">⏸ Pause</span>
        </button>
    </div>

    <!-- Live Counter -->
    <div class="glass-card" style="padding: 40px; text-align: center; margin-bottom: 30px; background: linear-gradient(135deg, rgba(14,165,233,0.1), rgba(16,185,129,0.1));">
        <div style="display: flex; justify-content: center; align-items: center; gap: 15px;">
            <span class="pulse" style="width: 20px; height: 20px; background: #10b981; border-radius: 50%; animation: pulse 2s infinite;"></span>
            <h2 style="font-size: 4rem; font-weight: bold; margin: 0;"><?= intval($active_visitors ?? 0) ?></h2>
        </div>
        <p style="font-size: 1.25rem; color: #64748b; margin-top: 10px;">Active Visitors Right Now</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Active Pages -->
        <div class="glass-card" style="padding: 20px;">
            <h3 style="font-size: 1.25rem; margin-bottom: 20px;">Active Pages</h3>
            <div style="max-height: 400px; overflow-y: auto;">
                <?php foreach ($active_pages ?? [] as $page): ?>
                <div style="padding: 15px; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex: 1; overflow: hidden;">
                        <p style="font-weight: 600; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= escape($page['url'] ?? '/') ?></p>
                        <p style="font-size: 0.875rem; color: #64748b;"><?= intval($page['visitors'] ?? 0) ?> visitor<?= intval($page['visitors'] ?? 0) !== 1 ? 's' : '' ?></p>
                    </div>
                    <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.875rem; font-weight: 600;"><?= intval($page['visitors'] ?? 0) ?></span>
                </div>
                <?php endforeach; ?>
                <?php if (empty($active_pages)): ?>
                <p style="padding: 40px; text-align: center; color: #64748b;">👀 No active pages right now</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Geographic Distribution -->
        <div class="glass-card" style="padding: 20px;">
            <h3 style="font-size: 1.25rem; margin-bottom: 20px;">Geographic Distribution</h3>
            <canvas id="geoChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Recent Activity Feed -->
    <div class="glass-card" style="padding: 20px;">
        <h3 style="font-size: 1.25rem; margin-bottom: 20px;">Recent Activity (Last 100)</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <th style="padding: 12px; text-align: left;">Time</th>
                        <th style="padding: 12px; text-align: left;">Page</th>
                        <th style="padding: 12px; text-align: left;">Location</th>
                        <th style="padding: 12px; text-align: left;">Device</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_activity ?? [] as $activity): ?>
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <td style="padding: 12px;"><?= escape($activity['time'] ?? 'Just now') ?></td>
                        <td style="padding: 12px;"><?= escape($activity['page'] ?? '/') ?></td>
                        <td style="padding: 12px;"><?= escape($activity['location'] ?? 'Unknown') ?></td>
                        <td style="padding: 12px;"><?= escape($activity['device'] ?? 'Desktop') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recent_activity)): ?>
                    <tr><td colspan="4" style="padding: 40px; text-align: center; color: #64748b;">📊 No recent activity</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const geoCtx = document.getElementById('geoChart');
new Chart(geoCtx, {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_column($geo_data ?? [], 'country')) ?: '["No data"]' ?>,
        datasets: [{
            data: <?= json_encode(array_column($geo_data ?? [], 'count')) ?: '[1]' ?>,
            backgroundColor: ['#0EA5E9', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#64748B']
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

let autoRefresh = true;
function toggleAutoRefresh() {
    autoRefresh = !autoRefresh;
    document.getElementById('refreshToggle').textContent = autoRefresh ? '⏸ Pause' : '▶ Resume';
    if (autoRefresh) startAutoRefresh();
}

function startAutoRefresh() {
    if (autoRefresh) {
        setTimeout(() => {
            location.reload();
        }, 5000);
    }
}
startAutoRefresh();

const style = document.createElement('style');
style.textContent = '@keyframes pulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.1); } }';
document.head.appendChild(style);
</script>
