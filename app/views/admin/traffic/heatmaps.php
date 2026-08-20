<?php defined('BASE_PATH') or exit('No direct script access allowed'); ?>
<?php $this->layout('layouts/admin', ['title' => 'Heatmaps & Scroll Maps']); ?>
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
<h1 style="font-size: 2rem; margin-bottom: 30px;">Heatmaps & Scroll Analysis</h1>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
<div class="glass-card" style="padding: 20px;"><h3>Click Heatmap</h3><p style="color: #64748b; margin-top: 10px;">Visual representation of where users click on your pages</p>
<div style="margin-top: 20px; padding: 40px; background: linear-gradient(135deg, rgba(14,165,233,0.1), rgba(239,68,68,0.1)); border-radius: 8px; text-align: center;"><p style="font-size: 2rem;">🔥</p><p style="color: #64748b;">Heatmap visualization</p></div></div>
<div class="glass-card" style="padding: 20px;"><h3>Scroll Depth</h3><p style="color: #64748b; margin-top: 10px;">Average scroll depth by page</p>
<canvas id="scrollChart" style="max-height: 300px; margin-top: 20px;"></canvas></div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>new Chart(document.getElementById('scrollChart'), {type: 'bar', data: {labels: ['0-25%', '25-50%', '50-75%', '75-100%'], datasets: [{label: 'Users', data: [100, 75, 50, 25], backgroundColor: '#0EA5E9'}]}, options: {responsive: true, maintainAspectRatio: false}});</script>
