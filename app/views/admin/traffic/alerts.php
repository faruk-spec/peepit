<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'Traffic Alerts']); ?>
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
<h1 style="font-size: 2rem; margin-bottom: 30px;">Traffic Alerts Configuration</h1>
<div class="glass-card" style="padding: 20px;"><h3>Alert Settings</h3>
<form method="post" action="/admin/traffic/alerts/save"><input type="hidden" name="csrf_token" value="<?= escape($csrf_token ?? '') ?>">
<div style="margin-bottom: 20px;"><label style="display: block; margin-bottom: 5px; font-weight: 600;">Traffic Spike Threshold (%)</label>
<input type="number" name="spike_threshold" value="200" min="100" max="1000" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px;"></div>
<div style="margin-bottom: 20px;"><label style="display: block; margin-bottom: 5px; font-weight: 600;">Email Notifications</label>
<input type="email" name="alert_email" placeholder="admin@example.com" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px;"></div>
<button type="submit" style="padding: 12px 24px; background: #0EA5E9; color: white; border: none; border-radius: 8px; cursor: pointer;">Save Alert Settings</button>
</form></div>
</div>
