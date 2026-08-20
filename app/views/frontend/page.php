<?php ob_start(); ?>

<section class="wave-bg" style="padding-top: 60px; padding-bottom: 60px;">
    <div class="container">
        <div class="glass-card" style="padding: 40px;">
            <h1 style="margin-bottom: 20px; color: var(--dark);">
                <?= htmlspecialchars($page['title']) ?>
            </h1>
            
            <div class="page-content" style="line-height: 1.8; color: var(--text);">
                <?= $page['content'] ?>
            </div>
            
            <?php if ($page['updated_at']): ?>
                <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--border); font-size: 0.875rem; color: var(--text-light);">
                    <i class="fas fa-clock"></i> Last updated: <?= date('F d, Y', strtotime($page['updated_at'])) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    .page-content {
        font-size: 1rem;
    }
    
    .page-content h2 {
        margin-top: 30px;
        margin-bottom: 15px;
        color: var(--primary);
    }
    
    .page-content h3 {
        margin-top: 25px;
        margin-bottom: 12px;
        color: var(--dark);
    }
    
    .page-content p {
        margin-bottom: 15px;
    }
    
    .page-content ul,
    .page-content ol {
        margin-bottom: 15px;
        padding-left: 30px;
    }
    
    .page-content li {
        margin-bottom: 8px;
    }
    
    .page-content a {
        color: var(--primary);
        text-decoration: none;
    }
    
    .page-content a:hover {
        text-decoration: underline;
    }
    
    .page-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .page-content blockquote {
        border-left: 4px solid var(--primary);
        padding-left: 20px;
        margin: 20px 0;
        font-style: italic;
        color: var(--text-light);
    }
    
    .page-content code {
        background: var(--light);
        padding: 2px 6px;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 0.9em;
    }
    
    .page-content pre {
        background: var(--light);
        padding: 15px;
        border-radius: 8px;
        overflow-x: auto;
        margin: 20px 0;
    }
    
    .page-content pre code {
        background: none;
        padding: 0;
    }
    
    .page-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    
    .page-content table th,
    .page-content table td {
        padding: 12px;
        border: 1px solid var(--border);
        text-align: left;
    }
    
    .page-content table th {
        background: var(--light);
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .glass-card {
            padding: 20px !important;
        }
        
        .page-content {
            font-size: 0.95rem;
        }
        
        .page-content h1 {
            font-size: 1.75rem;
        }
        
        .page-content h2 {
            font-size: 1.5rem;
        }
        
        .page-content h3 {
            font-size: 1.25rem;
        }
    }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/frontend.php';
?>
