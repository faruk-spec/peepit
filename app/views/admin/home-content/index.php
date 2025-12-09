<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Content Editor - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
    <?php require_once __DIR__ . '/../../layouts/admin.php'; ?>

    <div class="admin-content">
        <div class="container-narrow">
            <div class="page-header">
                <h1>🏠 Homepage Content Editor</h1>
                <p class="text-muted">Manage editable text sections on the homepage</p>
            </div>

            <?php if (has_flash()): ?>
                <div class="alert alert-<?= flash_type() ?>">
                    <?= get_flash() ?>
                </div>
            <?php endif; ?>

            <div class="glass-card" style="margin-bottom: 20px; padding: 20px; background: rgba(14, 165, 233, 0.05);">
                <h3 style="margin-bottom: 10px;"><i class="fas fa-info-circle"></i> About This Editor</h3>
                <p style="color: #64748b; line-height: 1.8; margin: 0;">
                    Edit the text content that appears on your homepage. These changes will be reflected immediately on the live site.
                    You can reset any section to its default value using the reset button.
                </p>
            </div>

            <form method="POST" action="<?= url('admin/home-content/update') ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                <!-- Hero Section -->
                <div class="glass-card" style="margin-bottom: 20px;">
                    <div style="padding: 20px; border-bottom: 2px solid #e5e7eb; background: rgba(14, 165, 233, 0.03);">
                        <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">
                            <span>🎯</span> Hero Section
                        </h2>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label for="hero_title">Hero Title</label>
                            <input type="text" 
                                   id="hero_title" 
                                   name="hero_title" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($sections['hero_title']['content'] ?? 'Welcome to Peepit') ?>">
                            <small class="text-muted">Main heading on the hero section</small>
                        </div>

                        <div class="form-group">
                            <label for="hero_description">Hero Description</label>
                            <input type="text" 
                                   id="hero_description" 
                                   name="hero_description" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($sections['hero_description']['content'] ?? 'Create Your Perfect Custom Water Bottle') ?>">
                            <small class="text-muted">Subheading/tagline on the hero section</small>
                        </div>

                        <div class="form-group">
                            <label for="hero_button_text">Hero Button Text</label>
                            <input type="text" 
                                   id="hero_button_text" 
                                   name="hero_button_text" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($sections['hero_button_text']['content'] ?? 'Get Started') ?>">
                            <small class="text-muted">Text on the main call-to-action button</small>
                        </div>
                    </div>
                </div>

                <!-- How It Works Section -->
                <div class="glass-card" style="margin-bottom: 20px;">
                    <div style="padding: 20px; border-bottom: 2px solid #e5e7eb; background: rgba(14, 165, 233, 0.03);">
                        <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">
                            <span>⚙️</span> How It Works Section
                        </h2>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label for="how_it_works_title">Section Title</label>
                            <input type="text" 
                                   id="how_it_works_title" 
                                   name="how_it_works_title" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($sections['how_it_works_title']['content'] ?? 'How It Works') ?>">
                        </div>

                        <div class="form-group">
                            <label for="how_it_works_description">Section Description</label>
                            <input type="text" 
                                   id="how_it_works_description" 
                                   name="how_it_works_description" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($sections['how_it_works_description']['content'] ?? 'Simple steps to get your custom water bottle') ?>">
                        </div>
                    </div>
                </div>

                <!-- Why Choose Section -->
                <div class="glass-card" style="margin-bottom: 20px;">
                    <div style="padding: 20px; border-bottom: 2px solid #e5e7eb; background: rgba(14, 165, 233, 0.03);">
                        <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">
                            <span>✨</span> Why Choose Us Section
                        </h2>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label for="why_choose_title">Section Title</label>
                            <input type="text" 
                                   id="why_choose_title" 
                                   name="why_choose_title" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($sections['why_choose_title']['content'] ?? 'Why Choose Peepit?') ?>">
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="glass-card" style="margin-bottom: 20px;">
                    <div style="padding: 20px; border-bottom: 2px solid #e5e7eb; background: rgba(14, 165, 233, 0.03);">
                        <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">
                            <span>📣</span> Call-to-Action Section
                        </h2>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label for="cta_title">CTA Title</label>
                            <input type="text" 
                                   id="cta_title" 
                                   name="cta_title" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($sections['cta_title']['content'] ?? 'Ready to Create Your Custom Bottle?') ?>">
                        </div>

                        <div class="form-group">
                            <label for="cta_description">CTA Description</label>
                            <textarea id="cta_description" 
                                      name="cta_description" 
                                      class="form-control" 
                                      rows="2"><?= htmlspecialchars($sections['cta_description']['content'] ?? 'Join thousands of satisfied customers who trust Peepit for their custom water bottle needs') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Statistics Section -->
                <div class="glass-card" style="margin-bottom: 20px;">
                    <div style="padding: 20px; border-bottom: 2px solid #e5e7eb; background: rgba(14, 165, 233, 0.03);">
                        <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">
                            <span>📊</span> Statistics Section
                        </h2>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label for="stats_title">Section Title</label>
                            <input type="text" 
                                   id="stats_title" 
                                   name="stats_title" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($sections['stats_title']['content'] ?? 'Trusted by Thousands') ?>">
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="display: flex; gap: 10px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i> Save All Changes
                    </button>
                    <a href="<?= url() ?>" class="btn btn-secondary" target="_blank">
                        <i class="fas fa-eye"></i> Preview Homepage
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column !important;
            }
            
            .form-actions .btn {
                width: 100%;
            }
        }
    </style>
</body>
</html>
