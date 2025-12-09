<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
    <?php require_once __DIR__ . '/../../layouts/admin.php'; ?>

    <div class="admin-content">
        <div class="container-narrow">
            <div class="page-header">
                <h1>⚙️ System Settings</h1>
                <p class="text-muted">Manage site configuration and system settings</p>
            </div>

            <?php if (has_flash()): ?>
                <div class="alert alert-<?= flash_type() ?>">
                    <?= flash() ?>
                </div>
            <?php endif; ?>

            <div class="glass-card">
                <form method="POST" action="<?= url('admin/settings/update') ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <?php if (isset($grouped_settings) && !empty($grouped_settings)): ?>
                        <?php foreach ($grouped_settings as $group => $settings): ?>
                            <div class="settings-group" style="margin-bottom: 40px;">
                                <h3 style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb;">
                                    <?= ucfirst($group) ?> Settings
                                </h3>
                                
                                <?php foreach ($settings as $setting): ?>
                                    <div class="form-group">
                                        <label for="<?= htmlspecialchars($setting['key']) ?>">
                                            <?= htmlspecialchars(ucwords(str_replace('_', ' ', $setting['key']))) ?>
                                        </label>
                                        <?php if (strlen($setting['value']) > 100): ?>
                                            <textarea 
                                                name="<?= htmlspecialchars($setting['key']) ?>" 
                                                id="<?= htmlspecialchars($setting['key']) ?>"
                                                rows="4"
                                                class="form-control"
                                            ><?= htmlspecialchars($setting['value']) ?></textarea>
                                        <?php else: ?>
                                            <input 
                                                type="text" 
                                                name="<?= htmlspecialchars($setting['key']) ?>" 
                                                id="<?= htmlspecialchars($setting['key']) ?>"
                                                value="<?= htmlspecialchars($setting['value']) ?>"
                                                class="form-control"
                                            >
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                💾 Save Settings
                            </button>
                            <a href="<?= url('admin/settings/smtp') ?>" class="btn btn-secondary">
                                📧 SMTP Settings
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center" style="padding: 40px;">
                            <p class="text-muted">No settings found in the database.</p>
                            <p class="text-muted">Settings will be automatically created when you configure them through the application.</p>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
