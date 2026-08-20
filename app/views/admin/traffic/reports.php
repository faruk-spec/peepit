<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'Custom Reports']); ?>
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
<h1 style="font-size: 2rem; margin-bottom: 30px;">Custom Reports & Export</h1>
<div class="glass-card" style="padding: 20px; margin-bottom: 30px;"><h3>Generate Report</h3>
<form method="post" action="/admin/traffic/reports/export"><input type="hidden" name="csrf_token" value="<?= escape($csrf_token ?? '') ?>">
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
<input type="date" name="start_date" placeholder="Start Date" style="padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px;">
<input type="date" name="end_date" placeholder="End Date" style="padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px;">
<select name="format" style="padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px;"><option value="csv">CSV</option><option value="pdf">PDF</option></select>
</div>
<button type="submit" style="padding: 12px 24px; background: #0EA5E9; color: white; border: none; border-radius: 8px; cursor: pointer;">Generate Report</button>
</form></div>
</div>
