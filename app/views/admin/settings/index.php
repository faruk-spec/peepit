<?php
$page_title = 'Settings';
$current_page = 'settings';
ob_start();
?>

<div class="page-breadcrumb mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4>System Settings</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
        <a href="<?= url('admin/settings/smtp') ?>" class="btn btn-secondary">
            <i class="fas fa-envelope me-2"></i> SMTP Settings
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Site Configuration</h5>
    </div>
    <div class="card-body">
        <?php if (isset($grouped_settings) && !empty($grouped_settings)): ?>
            <form method="POST" action="<?= url('admin/settings/update') ?>">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <?php foreach ($grouped_settings as $group => $settings): ?>
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <?= ucfirst($group) ?> Settings
                        </h5>
                        
                        <?php foreach ($settings as $setting): ?>
                            <div class="mb-3">
                                <label for="<?= htmlspecialchars($setting['key']) ?>" class="form-label">
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

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Settings
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="text-center py-5">
                <p class="text-muted">No settings found in the database.</p>
                <p class="text-muted">Settings will be automatically created when you configure them through the application.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
?>
