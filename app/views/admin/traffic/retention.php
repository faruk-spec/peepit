<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'User Retention']); ?>
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
<h1 style="font-size: 2rem; margin-bottom: 30px;">User Retention & Returning Visitors</h1>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
<div class="glass-card" style="padding: 20px;"><h3 style="font-size: 0.875rem; color: #64748b; margin-bottom: 10px;">RETURNING VISITORS</h3>
<p style="font-size: 2rem; font-weight: bold;"><?= number_format(intval($returning_visitors ?? 0)) ?></p></div>
<div class="glass-card" style="padding: 20px;"><h3 style="font-size: 0.875rem; color: #64748b; margin-bottom: 10px;">RETENTION RATE</h3>
<p style="font-size: 2rem; font-weight: bold;"><?= number_format(floatval($retention_rate ?? 0), 1) ?>%</p></div>
</div>
<div class="glass-card" style="padding: 20px;"><h3>Cohort Analysis</h3>
<table style="width: 100%;"><thead><tr><th style="text-align: left; padding: 10px;">Week</th><th style="text-align: right; padding: 10px;">New Users</th><th style="text-align: right; padding: 10px;">Week 1</th><th style="text-align: right; padding: 10px;">Week 2</th><th style="text-align: right; padding: 10px;">Week 3</th></tr></thead><tbody>
<?php foreach($cohorts ?? [] as $cohort): ?><tr><td style="padding: 10px;"><?= escape($cohort['week']) ?></td><td style="text-align: right; padding: 10px;"><?= number_format(intval($cohort['new_users'])) ?></td><td style="text-align: right; padding: 10px;"><?= number_format(floatval($cohort['week1']), 1) ?>%</td><td style="text-align: right; padding: 10px;"><?= number_format(floatval($cohort['week2']), 1) ?>%</td><td style="text-align: right; padding: 10px;"><?= number_format(floatval($cohort['week3']), 1) ?>%</td></tr><?php endforeach; ?>
<?php if(empty($cohorts)): ?><tr><td colspan="5" style="padding: 40px; text-align: center; color: #64748b;">📊 No cohort data available</td></tr><?php endif; ?></tbody></table>
</div>
</div>
