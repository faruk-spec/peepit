<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hero Slider Management - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
    <?php require_once __DIR__ . '/../../layouts/admin.php'; ?>

    <div class="admin-content">
        <div class="container">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div>
                    <h1>🖼️ Hero Slider Management</h1>
                    <p class="text-muted">Manage homepage hero section slider images</p>
                </div>
                <a href="<?= url('admin/hero-slider/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Hero Slide
                </a>
            </div>

            <?php if (has_flash()): ?>
                <div class="alert alert-<?= flash_type() ?>">
                    <?= get_flash() ?>
                </div>
            <?php endif; ?>

            <div class="glass-card" style="margin-bottom: 20px; padding: 20px; background: rgba(14, 165, 233, 0.05);">
                <h3 style="margin-bottom: 10px;"><i class="fas fa-info-circle"></i> Recommended Image Specifications</h3>
                <ul style="color: #64748b; line-height: 1.8; margin: 0;">
                    <li><strong>Dimensions:</strong> 1920x1080 pixels (16:9 ratio) for best results</li>
                    <li><strong>File Size:</strong> Maximum 5MB per image</li>
                    <li><strong>Formats:</strong> JPEG, PNG, or WebP</li>
                    <li><strong>Note:</strong> Hero text (H1, P, buttons) will overlay these images</li>
                </ul>
            </div>

            <?php if (!empty($slides)): ?>
                <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
                    <?php foreach ($slides as $slide): ?>
                        <div class="glass-card" style="padding: 0; overflow: hidden;">
                            <!-- Image Preview -->
                            <div style="position: relative; padding-top: 56.25%; background: #f1f5f9;">
                                <img src="<?= url('uploads/hero/' . htmlspecialchars($slide['image'])) ?>" 
                                     alt="<?= htmlspecialchars($slide['image_alt'] ?: $slide['title']) ?>"
                                     style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                                
                                <!-- Status Badge -->
                                <div style="position: absolute; top: 10px; right: 10px;">
                                    <?php if ($slide['status'] === 'active'): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactive</span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Order Badge -->
                                <div style="position: absolute; top: 10px; left: 10px;">
                                    <span class="badge badge-primary">Order: <?= intval($slide['order']) ?></span>
                                </div>
                            </div>
                            
                            <!-- Slide Info -->
                            <div style="padding: 20px;">
                                <?php if ($slide['title']): ?>
                                    <h3 style="margin: 0 0 10px; font-size: 1.1rem;">
                                        <?= htmlspecialchars($slide['title']) ?>
                                    </h3>
                                <?php endif; ?>
                                
                                <?php if ($slide['description']): ?>
                                    <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 15px;">
                                        <?= htmlspecialchars(substr($slide['description'], 0, 100)) ?>
                                        <?= strlen($slide['description']) > 100 ? '...' : '' ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if ($slide['button_text']): ?>
                                    <div style="margin-bottom: 15px; padding: 10px; background: rgba(14, 165, 233, 0.05); border-radius: 6px;">
                                        <strong style="font-size: 0.85rem; color: #64748b;">Button:</strong>
                                        <span style="font-size: 0.9rem;"><?= htmlspecialchars($slide['button_text']) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Actions -->
                                <div style="display: flex; gap: 10px; margin-top: 15px;">
                                    <a href="<?= url('admin/hero-slider/edit/' . $slide['id']) ?>" 
                                       class="btn btn-sm btn-primary" 
                                       style="flex: 1;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="<?= url('admin/hero-slider/delete') ?>" 
                                          method="POST" 
                                          style="flex: 1;"
                                          onsubmit="return confirm('Are you sure you want to delete this slide?');">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                        <input type="hidden" name="id" value="<?= $slide['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" style="width: 100%;">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="glass-card" style="padding: 60px; text-align: center;">
                    <div style="font-size: 4rem; margin-bottom: 20px;">🖼️</div>
                    <h3 style="color: #64748b; margin-bottom: 10px;">No Hero Slides Yet</h3>
                    <p style="color: #94a3b8; margin-bottom: 20px;">Create your first hero slide for the homepage.</p>
                    <a href="<?= url('admin/hero-slider/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Hero Slide
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 15px;
            }
            
            .page-header .btn {
                width: 100%;
            }
            
            .grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</body>
</html>
