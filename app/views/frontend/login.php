<?php ob_start(); ?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Login to Peepit</h2>
        
        <form method="POST" action="<?= url('login') ?>" id="login-form">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
        
        <p class="text-center mt-20">
            Don't have an account? <a href="<?= url('register') ?>">Register here</a>
        </p>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Login - Peepit';
include __DIR__ . '/../layouts/frontend.php';
?>
