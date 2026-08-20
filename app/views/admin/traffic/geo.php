<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'Geographic Distribution']); ?>
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
<h1 style="font-size: 2rem; margin-bottom: 30px;">Geographic Distribution</h1>
<div class="glass-card" style="padding: 20px; margin-bottom: 30px;"><h3>Top Countries</h3><canvas id="geoChart" style="max-height: 300px;"></canvas></div>
<div class="glass-card" style="padding: 20px;"><h3>Country Breakdown</h3>
<table style="width: 100%;"><thead><tr><th style="text-align: left; padding: 10px;">Country</th><th style="text-align: right; padding: 10px;">Visitors</th><th style="text-align: right; padding: 10px;">%</th></tr></thead>
<tbody><?php foreach($countries ?? [] as $country): ?><tr><td style="padding: 10px;"><?= escape($country['name']) ?></td><td style="text-align: right; padding: 10px;"><?= number_format(intval($country['count'])) ?></td><td style="text-align: right; padding: 10px;"><?= number_format(floatval($country['percent']), 1) ?>%</td></tr><?php endforeach; ?>
<?php if(empty($countries)): ?><tr><td colspan="3" style="padding: 40px; text-align: center; color: #64748b;">🌍 No geographic data</td></tr><?php endif; ?></tbody></table></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>new Chart(document.getElementById('geoChart'), {type: 'bar', data: {labels: <?= json_encode(array_column($countries ?? [], 'name')) ?: '[]' ?>, datasets: [{label: 'Visitors', data: <?= json_encode(array_column($countries ?? [], 'count')) ?: '[]' ?>, backgroundColor: '#0EA5E9'}]}, options: {responsive: true, maintainAspectRatio: false, indexAxis: 'y'}});</script>
