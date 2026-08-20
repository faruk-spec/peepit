<?php ob_start(); ?>

<div class="page-breadcrumb mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4>Label Templates</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Label Templates</li>
                </ol>
            </nav>
        </div>
        <a href="<?= url('admin/templates/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i> Add New Template
        </a>
    </div>
</div>

<?php if (!empty($templates)): ?>
    <div class="row g-3">
        <?php foreach ($templates as $template): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <?php if ($template['image']): ?>
                        <img src="<?= url('uploads/templates/' . escape($template['image'])) ?>" 
                             alt="<?= escape($template['name']) ?>"
                             class="card-img-top"
                             style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                            <i class="fas fa-image text-muted" style="font-size: 64px;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?= escape($template['name']) ?></h5>
                        <?php if ($template['category']): ?>
                            <span class="badge bg-primary mb-2"><?= escape($template['category']) ?></span>
                        <?php endif; ?>
                        
                        <?php if ($template['description']): ?>
                            <p class="card-text text-muted small">
                                <?= escape(substr($template['description'], 0, 80)) ?><?= strlen($template['description']) > 80 ? '...' : '' ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="badge bg-<?= $template['status'] === 'active' ? 'success' : 'danger' ?>">
                                <?= ucfirst($template['status']) ?>
                            </span>
                            <div class="d-flex gap-2">
                                <a href="<?= url('admin/templates/edit/' . $template['id']) ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" 
                                      action="<?= url('admin/templates/delete/' . $template['id']) ?>" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <div style="font-size: 64px; color: #8897ad; margin-bottom: 20px;">
                <i class="fas fa-tags"></i>
            </div>
            <h3 class="mb-3">No Label Templates Yet</h3>
            <p class="text-muted mb-4">Create templates for customers to use on their bottles</p>
            <a href="<?= url('admin/templates/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Add First Template
            </a>
        </div>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$page_title = 'Label Templates';
$current_page = 'templates';
include __DIR__ . '/../../layouts/admin.php';
?>
