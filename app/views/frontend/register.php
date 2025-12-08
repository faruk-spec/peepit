<?php ob_start(); ?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Register for Peepit</h2>
        
        <form method="POST" action="<?= url('register') ?>" id="register-form">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" value="<?= old('name') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?= old('phone') ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password * (min 8 characters)</label>
                <input type="password" id="password" name="password" required minlength="8">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Register</button>
        </form>
        
        <p class="text-center mt-20">
            Already have an account? <a href="<?= url('login') ?>">Login here</a>
        </p>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Register - Peepit';
include __DIR__ . '/../layouts/frontend.php';
?>
