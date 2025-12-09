<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'UTM Campaigns']); ?>
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
<h1 style="font-size: 2rem; margin-bottom: 30px;">UTM Campaign Tracking</h1>
<div class="glass-card" style="padding: 20px;"><h3>Campaign Performance</h3>
<table style="width: 100%;"><thead><tr><th style="text-align: left; padding: 10px;">Campaign</th><th style="text-align: left; padding: 10px;">Source</th><th style="text-align: left; padding: 10px;">Medium</th><th style="text-align: right; padding: 10px;">Visitors</th><th style="text-align: right; padding: 10px;">Conversions</th></tr></thead><tbody>
<?php foreach($campaigns ?? [] as $campaign): ?><tr><td style="padding: 10px;"><?= escape($campaign['campaign']) ?></td><td style="padding: 10px;"><?= escape($campaign['source']) ?></td><td style="padding: 10px;"><?= escape($campaign['medium']) ?></td><td style="text-align: right; padding: 10px;"><?= number_format(intval($campaign['visitors'])) ?></td><td style="text-align: right; padding: 10px;"><?= number_format(intval($campaign['conversions'])) ?></td></tr><?php endforeach; ?>
<?php if(empty($campaigns)): ?><tr><td colspan="5" style="padding: 40px; text-align: center; color: #64748b;">📊 No campaign data</td></tr><?php endif; ?></tbody></table></div>
</div>
