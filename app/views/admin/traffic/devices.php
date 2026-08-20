<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'Devices & Browsers']); ?>
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
<h1 style="font-size: 2rem; margin-bottom: 30px;">Devices & Browsers</h1>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
<div class="glass-card" style="padding: 20px;"><h3>Device Types</h3><canvas id="deviceChart" style="max-height: 250px;"></canvas></div>
<div class="glass-card" style="padding: 20px;"><h3>Browsers</h3><canvas id="browserChart" style="max-height: 250px;"></canvas></div>
<div class="glass-card" style="padding: 20px;"><h3>Operating Systems</h3><canvas id="osChart" style="max-height: 250px;"></canvas></div>
</div></div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('deviceChart'), {type: 'pie', data: {labels: ['Desktop', 'Mobile', 'Tablet'], datasets: [{data: <?= json_encode($device_data ?? [60, 35, 5]) ?>, backgroundColor: ['#0EA5E9', '#10B981', '#F59E0B']}]}, options: {responsive: true, maintainAspectRatio: false}});
new Chart(document.getElementById('browserChart'), {type: 'pie', data: {labels: ['Chrome', 'Safari', 'Firefox', 'Edge', 'Other'], datasets: [{data: <?= json_encode($browser_data ?? [50, 25, 15, 8, 2]) ?>, backgroundColor: ['#0EA5E9', '#10B981', '#F59E0B', '#EF4444', '#64748B']}]}, options: {responsive: true, maintainAspectRatio: false}});
new Chart(document.getElementById('osChart'), {type: 'pie', data: {labels: ['Windows', 'macOS', 'Linux', 'iOS', 'Android'], datasets: [{data: <?= json_encode($os_data ?? [45, 25, 5, 15, 10]) ?>, backgroundColor: ['#0EA5E9', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6']}]}, options: {responsive: true, maintainAspectRatio: false}});
</script>
