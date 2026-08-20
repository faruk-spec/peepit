<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'Traffic Sources']); ?>
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
<h1 style="font-size: 2rem; margin-bottom: 30px;">Traffic Sources</h1>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
<div class="glass-card" style="padding: 20px;"><h3>Source Distribution</h3><canvas id="sourcesChart" style="max-height: 300px;"></canvas></div>
<div class="glass-card" style="padding: 20px;"><h3>Top Referrers</h3>
<table style="width: 100%;"><thead><tr><th style="text-align: left; padding: 10px;">Source</th><th style="text-align: right; padding: 10px;">Visits</th></tr></thead>
<tbody><?php foreach($referrers ?? [] as $ref): ?><tr><td style="padding: 10px;"><?= escape($ref['source']) ?></td><td style="text-align: right; padding: 10px;"><?= number_format(intval($ref['visits'])) ?></td></tr><?php endforeach; ?>
<?php if(empty($referrers)): ?><tr><td colspan="2" style="padding: 40px; text-align: center; color: #64748b;">📊 No data available</td></tr><?php endif; ?></tbody></table></div>
</div></div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>new Chart(document.getElementById('sourcesChart'), {type: 'pie', data: {labels: ['Direct', 'Google', 'Social', 'Referral', 'Email', 'Other'], datasets: [{data: <?= json_encode($sources_data ?? [35, 30, 15, 10, 5, 5]) ?>, backgroundColor: ['#0EA5E9', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#64748B']}]}, options: {responsive: true, maintainAspectRatio: false}});</script>
