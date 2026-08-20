<?php ob_start(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <!-- Progress Steps -->
    <div class="order-progress">
        <div class="step completed">
            <div class="step-number"><i class="fas fa-check"></i></div>
            <div class="step-label">Select Model</div>
        </div>
        <div class="step completed">
            <div class="step-number"><i class="fas fa-check"></i></div>
            <div class="step-label">Choose Size</div>
        </div>
        <div class="step completed">
            <div class="step-number"><i class="fas fa-check"></i></div>
            <div class="step-label">Select Color</div>
        </div>
        <div class="step active">
            <div class="step-number">4</div>
            <div class="step-label">Design Label</div>
        </div>
        <div class="step">
            <div class="step-number">5</div>
            <div class="step-label">Quantity</div>
        </div>
        <div class="step">
            <div class="step-number">6</div>
            <div class="step-label">Delivery</div>
        </div>
        <div class="step">
            <div class="step-number">7</div>
            <div class="step-label">Summary</div>
        </div>
    </div>

    <div class="card glass-card" style="margin-top: 40px;">
        <h2 class="mb-20"><i class="fas fa-tag"></i> Step 4: Design Label</h2>
        <p class="text-light mb-30">Upload your custom design or choose from our templates</p>

        <form method="POST" action="<?= url('order/step5') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Upload Custom Design -->
            <div class="design-section mb-40">
                <h3 class="mb-20"><i class="fas fa-upload"></i> Upload Custom Design</h3>
                <div class="upload-area" id="uploadArea">
                    <input type="file" 
                           name="label_image" 
                           id="labelImage" 
                           accept="image/png,image/jpeg,image/jpg"
                           class="file-input">
                    <label for="labelImage" class="upload-label">
                        <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <h4>Click to upload or drag & drop</h4>
                        <p>PNG, JPG up to 10MB</p>
                        <div class="preview-image" id="previewImage"></div>
                    </label>
                </div>
            </div>

            <!-- Label Templates -->
            <?php if (!empty($templates)): ?>
                <div class="design-section">
                    <h3 class="mb-20"><i class="fas fa-images"></i> Or Choose a Template</h3>
                    <div class="templates-grid">
                        <?php foreach ($templates as $template): ?>
                            <div class="template-item">
                                <input type="radio" 
                                       name="label_design" 
                                       value="<?= $template['id'] ?>" 
                                       id="template-<?= $template['id'] ?>"
                                       class="template-radio">
                                <label for="template-<?= $template['id'] ?>" class="template-label">
                                    <?php if ($template['image']): ?>
                                        <img src="<?= url('uploads/templates/' . escape($template['image'])) ?>" 
                                             alt="<?= escape($template['name']) ?>">
                                    <?php else: ?>
                                        <div class="template-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="template-info">
                                        <h4><?= escape($template['name']) ?></h4>
                                        <?php if ($template['category']): ?>
                                            <span class="template-category"><?= escape($template['category']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="template-check"><i class="fas fa-check-circle"></i></div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Skip Option -->
            <div class="design-section mt-30">
                <label class="skip-design">
                    <input type="checkbox" name="skip_design" value="1" id="skipDesign">
                    <span><i class="fas fa-times-circle"></i> Skip label design (plain bottle)</span>
                </label>
            </div>

            <div class="form-actions">
                <a href="<?= url('order/step3') ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    Continue <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .order-progress {
        display: flex;
        justify-content: space-between;
        max-width: 900px;
        margin: 0 auto;
        position: relative;
        padding: 0 20px;
    }

    .order-progress::before {
        content: '';
        position: absolute;
        top: 30px;
        left: 40px;
        right: 40px;
        height: 3px;
        background: var(--border);
        z-index: 0;
    }

    .step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .step-number {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
        font-size: 20px;
        color: var(--text-light);
        position: relative;
        z-index: 1;
    }

    .step.active .step-number {
        background: var(--gradient-primary);
        color: white;
        border-color: var(--primary);
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
    }

    .step.completed .step-number {
        background: var(--success);
        color: white;
        border-color: var(--success);
    }

    .step-label {
        font-size: 13px;
        color: var(--text-light);
        font-weight: 500;
    }

    .step.active .step-label {
        color: var(--primary);
        font-weight: 600;
    }

    .design-section {
        padding: 20px;
        background: var(--light);
        border-radius: 12px;
    }

    .upload-area {
        position: relative;
    }

    .file-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .upload-label {
        display: block;
        padding: 60px 20px;
        border: 3px dashed var(--border);
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: white;
    }

    .upload-label:hover {
        border-color: var(--primary);
        background: var(--light);
    }

    .upload-icon {
        font-size: 64px;
        color: var(--primary);
        margin-bottom: 20px;
    }

    .upload-label h4 {
        margin: 0 0 10px;
        color: var(--dark);
    }

    .upload-label p {
        margin: 0;
        color: var(--text-light);
        font-size: 14px;
    }

    .preview-image {
        margin-top: 20px;
        display: none;
    }

    .preview-image img {
        max-width: 300px;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .templates-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .template-item {
        position: relative;
    }

    .template-radio {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .template-label {
        display: block;
        background: white;
        border: 3px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }

    .template-label:hover {
        border-color: var(--primary);
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.2);
    }

    .template-radio:checked + .template-label {
        border-color: var(--primary);
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.15);
    }

    .template-label img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .template-placeholder {
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--light);
        font-size: 64px;
        color: var(--text-light);
    }

    .template-info {
        padding: 15px;
    }

    .template-info h4 {
        margin: 0 0 5px;
        font-size: 16px;
        color: var(--dark);
    }

    .template-category {
        display: inline-block;
        padding: 3px 10px;
        background: var(--light);
        border-radius: 12px;
        font-size: 12px;
        color: var(--text-light);
    }

    .template-check {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 35px;
        height: 35px;
        background: var(--success);
        color: white;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .template-radio:checked + .template-label .template-check {
        display: flex;
    }

    .skip-design {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        padding: 15px;
        background: white;
        border-radius: 8px;
        font-weight: 500;
    }

    .skip-design input {
        width: 20px;
        height: 20px;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid var(--border);
    }

    @media (max-width: 768px) {
        .order-progress {
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .step {
            min-width: 70px;
        }

        .step-number {
            width: 45px;
            height: 45px;
            font-size: 16px;
        }

        .step-label {
            font-size: 11px;
        }

        .templates-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }
    }
</style>

<script>
    const labelImage = document.getElementById('labelImage');
    const previewImage = document.getElementById('previewImage');
    const uploadArea = document.getElementById('uploadArea');

    labelImage.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImage.innerHTML = '<img src="' + event.target.result + '" alt="Preview">';
                previewImage.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = 'var(--primary)';
    });

    uploadArea.addEventListener('dragleave', function() {
        this.style.borderColor = 'var(--border)';
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = 'var(--border)';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            // Create a new change event
            const dt = new DataTransfer();
            dt.items.add(files[0]);
            labelImage.files = dt.files;
            
            // Trigger change event
            const event = new Event('change', { bubbles: true });
            labelImage.dispatchEvent(event);
        }
    });
</script>

<?php
$content = ob_get_clean();
$title = 'Design Label - Order - Peepit';
include __DIR__ . '/../../layouts/frontend.php';
?>
