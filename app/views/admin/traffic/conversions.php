<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'Conversion Tracking']); ?>
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
<h1 style="font-size: 2rem; margin-bottom: 30px;">Conversion Tracking & Funnels</h1>
<div class="glass-card" style="padding: 20px; margin-bottom: 30px;"><h3>Conversion Goals</h3>
<table style="width: 100%;"><thead><tr><th style="text-align: left; padding: 10px;">Goal</th><th style="text-align: right; padding: 10px;">Conversions</th><th style="text-align: right; padding: 10px;">Rate</th></tr></thead><tbody>
<?php foreach($goals ?? [] as $goal): ?><tr><td style="padding: 10px;"><?= escape($goal['name']) ?></td><td style="text-align: right; padding: 10px;"><?= number_format(intval($goal['conversions'])) ?></td><td style="text-align: right; padding: 10px;"><?= number_format(floatval($goal['rate']), 2) ?>%</td></tr><?php endforeach; ?>
<?php if(empty($goals)): ?><tr><td colspan="3" style="padding: 40px; text-align: center; color: #64748b;">🎯 No conversion goals configured</td></tr><?php endif; ?></tbody></table>
</div>
<div class="glass-card" style="padding: 20px;"><h3>Create New Goal</h3>
<form method="post" action="/admin/traffic/conversions/goal"><input type="hidden" name="csrf_token" value="<?= escape($csrf_token ?? '') ?>">
<input type="text" name="goal_name" placeholder="Goal Name" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 15px;">
<select name="goal_type" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 15px;"><option value="url">URL-based</option><option value="event">Event-based</option></select>
<input type="text" name="goal_value" placeholder="URL or Event Name" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 15px;">
<button type="submit" style="padding: 12px 24px; background: #0EA5E9; color: white; border: none; border-radius: 8px; cursor: pointer;">Create Goal</button>
</form></div>
</div>
