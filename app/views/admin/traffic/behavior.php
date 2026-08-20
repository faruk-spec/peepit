<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'User Behavior']); ?>
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
<h1 style="font-size: 2rem; margin-bottom: 30px;">User Behavior Analysis</h1>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
<div class="glass-card" style="padding: 20px;"><h3>Avg Session Duration</h3><canvas id="durationChart" style="max-height: 250px;"></canvas></div>
<div class="glass-card" style="padding: 20px;"><h3>Bounce Rate</h3><canvas id="bounceChart" style="max-height: 250px;"></canvas></div>
</div>
<div class="glass-card" style="padding: 20px;"><h3>Top Entry Pages</h3><table style="width: 100%;"><thead><tr><th style="text-align: left; padding: 10px;">Page</th><th style="text-align: right; padding: 10px;">Visits</th></tr></thead><tbody>
<?php foreach($entry_pages ?? [] as $page): ?><tr><td style="padding: 10px;"><?= escape($page['url']) ?></td><td style="text-align: right; padding: 10px;"><?= number_format(intval($page['count'])) ?></td></tr><?php endforeach; ?>
<?php if(empty($entry_pages)): ?><tr><td colspan="2" style="padding: 40px; text-align: center; color: #64748b;">📊 No data</td></tr><?php endif; ?></tbody></table></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('durationChart'), {type: 'line', data: {labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], datasets: [{label: 'Avg Duration (min)', data: [2.5, 2.8, 3.1, 2.9, 3.3, 2.7, 2.4], borderColor: '#0EA5E9', tension: 0.4}]}, options: {responsive: true, maintainAspectRatio: false}});
new Chart(document.getElementById('bounceChart'), {type: 'line', data: {labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], datasets: [{label: 'Bounce Rate (%)', data: [45, 48, 42, 47, 40, 50, 52], borderColor: '#EF4444', tension: 0.4}]}, options: {responsive: true, maintainAspectRatio: false}});
</script>
