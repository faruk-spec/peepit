<?php ob_start(); ?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Admin Login</h2>
        <p class="text-center mb-20" style="color: #718096;">Enter your credentials to access the admin panel</p>
        
        <form method="POST" action="<?= url('admin/login') ?>" id="admin-login-form">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login to Admin Panel</button>
        </form>
        
        <p class="text-center mt-20">
            <a href="<?= url() ?>">← Back to Home</a>
        </p>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    nav, footer {
        display: none;
    }
</style>

<?php
$content = ob_get_clean();
$title = 'Admin Login - Peepit';
include __DIR__ . '/../layouts/frontend.php';
?>
