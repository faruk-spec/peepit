<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'Traffic Dashboard']); ?>

<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <div class="page-header" style="margin-bottom: 30px;">
        <h1 style="font-size: 2rem; margin-bottom: 10px;">Traffic Dashboard</h1>
        <p style="color: #64748b;">Real-time visitor analytics and insights</p>
    </div>

    <?php if (has_flash()): ?>
        <div class="alert alert-<?= flash_type() ?>" style="margin-bottom: 20px; padding: 15px; border-radius: 8px; background: rgba(<?= flash_type() === 'success' ? '16,185,129' : '239,68,68' ?>, 0.1);">
            <?= get_flash() ?>
        </div>
    <?php endif; ?>

    <!-- Real-time Stats -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="glass-card" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h3 style="font-size: 0.875rem; color: #64748b; font-weight: 600;">VISITORS NOW</h3>
                <span class="pulse" style="width: 12px; height: 12px; background: #10b981; border-radius: 50%; animation: pulse 2s infinite;"></span>
            </div>
            <p style="font-size: 2rem; font-weight: bold; margin: 10px 0;"><?= intval($active_visitors ?? 0) ?></p>
            <p style="font-size: 0.875rem; color: #64748b;">Active on site</p>
        </div>

        <div class="glass-card" style="padding: 20px;">
            <h3 style="font-size: 0.875rem; color: #64748b; font-weight: 600; margin-bottom: 10px;">TODAY'S PAGEVIEWS</h3>
            <p style="font-size: 2rem; font-weight: bold; margin: 10px 0;"><?= number_format(intval($pageviews_today ?? 0)) ?></p>
            <p style="font-size: 0.875rem; color: <?= ($pageviews_change ?? 0) >= 0 ? '#10b981' : '#ef4444' ?>;">
                <?= ($pageviews_change ?? 0) >= 0 ? '↑' : '↓' ?> <?= abs(intval($pageviews_change ?? 0)) ?>% vs yesterday
            </p>
        </div>

        <div class="glass-card" style="padding: 20px;">
            <h3 style="font-size: 0.875rem; color: #64748b; font-weight: 600; margin-bottom: 10px;">UNIQUE VISITORS</h3>
            <p style="font-size: 2rem; font-weight: bold; margin: 10px 0;"><?= number_format(intval($unique_visitors ?? 0)) ?></p>
            <p style="font-size: 0.875rem; color: <?= ($visitors_change ?? 0) >= 0 ? '#10b981' : '#ef4444' ?>;">
                <?= ($visitors_change ?? 0) >= 0 ? '↑' : '↓' ?> <?= abs(intval($visitors_change ?? 0)) ?>% vs yesterday
            </p>
        </div>

        <div class="glass-card" style="padding: 20px;">
            <h3 style="font-size: 0.875rem; color: #64748b; font-weight: 600; margin-bottom: 10px;">AVG SESSION</h3>
            <p style="font-size: 2rem; font-weight: bold; margin: 10px 0;"><?= escape($avg_session ?? '2m 34s') ?></p>
            <p style="font-size: 0.875rem; color: #64748b;">Duration per visit</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Trend Chart -->
        <div class="glass-card" style="padding: 20px; grid-column: span 2;">
            <h3 style="font-size: 1.25rem; margin-bottom: 20px;">7-Day Trend</h3>
            <canvas id="trendChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Traffic Sources Pie -->
        <div class="glass-card" style="padding: 20px;">
            <h3 style="font-size: 1.25rem; margin-bottom: 20px;">Traffic Sources</h3>
            <canvas id="sourcesChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Top Pages Table -->
    <div class="glass-card" style="padding: 20px;">
        <h3 style="font-size: 1.25rem; margin-bottom: 20px;">Top 10 Pages</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Page</th>
                        <th style="padding: 12px; text-align: right; font-weight: 600;">Pageviews</th>
                        <th style="padding: 12px; text-align: right; font-weight: 600;">Unique</th>
                        <th style="padding: 12px; text-align: right; font-weight: 600;">Avg Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_pages ?? [] as $page): ?>
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <td style="padding: 12px;"><?= escape($page['url'] ?? '/') ?></td>
                        <td style="padding: 12px; text-align: right;"><?= number_format(intval($page['views'] ?? 0)) ?></td>
                        <td style="padding: 12px; text-align: right;"><?= number_format(intval($page['unique'] ?? 0)) ?></td>
                        <td style="padding: 12px; text-align: right;"><?= escape($page['avg_time'] ?? '0s') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($top_pages)): ?>
                    <tr>
                        <td colspan="4" style="padding: 40px; text-align: center; color: #64748b;">
                            📊 No data available yet
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Trend Chart
const trendCtx = document.getElementById('trendChart');
new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($trend_labels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) ?>,
        datasets: [{
            label: 'Pageviews',
            data: <?= json_encode($trend_data ?? [120, 150, 180, 160, 200, 190, 220]) ?>,
            borderColor: '#0EA5E9',
            backgroundColor: 'rgba(14, 165, 233, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Sources Pie Chart
const sourcesCtx = document.getElementById('sourcesChart');
new Chart(sourcesCtx, {
    type: 'pie',
    data: {
        labels: ['Direct', 'Google', 'Social', 'Referral', 'Email', 'Other'],
        datasets: [{
            data: <?= json_encode($sources_data ?? [35, 30, 15, 10, 5, 5]) ?>,
            backgroundColor: ['#0EA5E9', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#64748B']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Pulse animation
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr) !important; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr !important; }
        canvas { max-height: 200px !important; }
    }
`;
document.head.appendChild(style);
</script>
