<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'Bot Detection']); ?>
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
<h1 style="font-size: 2rem; margin-bottom: 30px;">Bot Traffic Detection & Filtering</h1>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
<div class="glass-card" style="padding: 20px;"><h3>Traffic Distribution</h3><canvas id="botChart" style="max-height: 250px;"></canvas></div>
<div class="glass-card" style="padding: 20px;"><h3>Bot Filter</h3><form method="post" action="/admin/traffic/bots/filter"><input type="hidden" name="csrf_token" value="<?= escape($csrf_token ?? '') ?>">
<label style="display: flex; align-items: center; gap: 10px; cursor: pointer;"><input type="checkbox" name="filter_bots" value="1" <?= ($filter_bots ?? false) ? 'checked' : '' ?> style="width: 20px; height: 20px;">
<span>Filter bot traffic from statistics</span></label><button type="submit" style="margin-top: 20px; padding: 12px 24px; background: #0EA5E9; color: white; border: none; border-radius: 8px; cursor: pointer;">Update Filter</button>
</form></div>
</div>
<div class="glass-card" style="padding: 20px;"><h3>Detected Bots</h3><table style="width: 100%;"><thead><tr><th style="text-align: left; padding: 10px;">Bot Name</th><th style="text-align: right; padding: 10px;">Requests</th></tr></thead><tbody>
<?php foreach($bots ?? [] as $bot): ?><tr><td style="padding: 10px;"><?= escape($bot['name']) ?></td><td style="text-align: right; padding: 10px;"><?= number_format(intval($bot['count'])) ?></td></tr><?php endforeach; ?>
<?php if(empty($bots)): ?><tr><td colspan="2" style="padding: 40px; text-align: center; color: #64748b;">🤖 No bot traffic detected</td></tr><?php endif; ?></tbody></table></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>new Chart(document.getElementById('botChart'), {type: 'pie', data: {labels: ['Human Traffic', 'Bot Traffic'], datasets: [{data: [<?= intval($human_traffic ?? 85) ?>, <?= intval($bot_traffic ?? 15) ?>], backgroundColor: ['#10B981', '#EF4444']}]}, options: {responsive: true, maintainAspectRatio: false}});</script>
