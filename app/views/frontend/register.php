<?php ob_start(); ?>

<div class="auth-container">
    <div class="auth-card glass-effect">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2>Create Account</h2>
            <p>Join Peepit to start creating custom water bottles</p>
        </div>
        
        <?php if (has_flash()): ?>
            <div class="alert alert-<?= flash_type() ?>">
                <?= get_flash() ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= url('register') ?>" id="register-form">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="name">
                    <i class="fas fa-user"></i> Full Name
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       class="form-control"
                       placeholder="Enter your full name"
                       value="<?= old('name') ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control"
                       placeholder="your@email.com"
                       value="<?= old('email') ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="phone">
                    <i class="fas fa-phone"></i> Phone Number
                </label>
                <input type="tel" 
                       id="phone" 
                       name="phone" 
                       class="form-control"
                       placeholder="+91 012345 6789"
                       pattern="[0-9+\s\-]+"
                       value="<?= old('phone') ?>"
                       required>
            </div>
            
            <div class="form-group">
                <label for="pincode">
                    <i class="fas fa-map-marker-alt"></i> Pincode
                </label>
                <input type="text" 
                       id="pincode" 
                       name="pincode" 
                       class="form-control"
                       placeholder="Enter 6-digit pincode"
                       pattern="[0-9]{6}"
                       maxlength="6"
                       value="<?= old('pincode') ?>"
                       required>
                <small class="text-muted" style="display: block; margin-top: 4px; font-size: 12px;">Must be 6 digits</small>
            </div>
            
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <div class="password-input-wrapper">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control"
                           placeholder="Minimum 8 characters"
                           required 
                           minlength="8">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password-eye"></i>
                    </button>
                </div>
                <div class="password-strength" id="password-strength"></div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">
                    <i class="fas fa-lock"></i> Confirm Password
                </label>
                <div class="password-input-wrapper">
                    <input type="password" 
                           id="confirm_password" 
                           name="confirm_password" 
                           class="form-control"
                           placeholder="Re-enter your password"
                           required 
                           minlength="8">
                    <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                        <i class="fas fa-eye" id="confirm_password-eye"></i>
                    </button>
                </div>
                <small class="password-match" id="password-match"></small>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block water-drop-btn">
                <i class="fas fa-rocket"></i> Create My Account
            </button>
        </form>
        
        <div class="auth-footer">
            <p>Already have an account? <a href="<?= url('login') ?>" class="link-primary">Login here</a></p>
        </div>
    </div>
    
    <div class="auth-background">
        <div class="water-drop drop-1"></div>
        <div class="water-drop drop-2"></div>
        <div class="water-drop drop-3"></div>
        <div class="water-drop drop-4"></div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        overflow-x: hidden;
    }
    
    body > .container {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 1;
        padding-top: 80px;
    }
    
    footer {
        display: none !important;
    }
    
    nav {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .auth-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        padding: 20px;
        z-index: 10;
        margin: 0 auto;
    }
    
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        animation: slideUp 0.6s ease-out;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .auth-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .auth-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: white;
        box-shadow: 0 10px 25px rgba(6, 182, 212, 0.4);
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 10px 25px rgba(6, 182, 212, 0.4);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 15px 35px rgba(6, 182, 212, 0.6);
        }
    }
    
    .auth-header h2 {
        margin: 0 0 10px;
        color: #2d3748;
        font-size: 28px;
    }
    
    .auth-header p {
        margin: 0;
        color: #718096;
        font-size: 15px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #4a5568;
        font-weight: 600;
        font-size: 14px;
    }
    
    .form-group label i {
        margin-right: 8px;
        color: #06b6d4;
    }
    
    .text-muted {
        color: #a0aec0;
        font-weight: 400;
    }
    
    .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s;
        background: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #06b6d4;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
    }
    
    .password-input-wrapper {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #a0aec0;
        cursor: pointer;
        padding: 8px;
        transition: color 0.3s;
    }
    
    .password-toggle:hover {
        color: #06b6d4;
    }
    
    .password-strength {
        margin-top: 8px;
        height: 4px;
        border-radius: 2px;
        background: #e2e8f0;
        overflow: hidden;
    }
    
    .password-strength::after {
        content: '';
        display: block;
        height: 100%;
        transition: width 0.3s, background 0.3s;
    }
    
    .password-strength.weak::after {
        width: 33%;
        background: #ef4444;
    }
    
    .password-strength.medium::after {
        width: 66%;
        background: #f59e0b;
    }
    
    .password-strength.strong::after {
        width: 100%;
        background: #10b981;
    }
    
    .password-match {
        display: block;
        margin-top: 6px;
        font-size: 13px;
    }
    
    .password-match.match {
        color: #10b981;
    }
    
    .password-match.no-match {
        color: #ef4444;
    }
    
    .btn-block {
        width: 100%;
        padding: 16px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 10px;
        margin-top: 10px;
    }
    
    .water-drop-btn {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%);
        border: none;
        transition: all 0.3s;
    }
    
    .water-drop-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(6, 182, 212, 0.4);
    }
    
    .water-drop-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .water-drop-btn:active::before {
        width: 300px;
        height: 300px;
    }
    
    .auth-footer {
        text-align: center;
        margin-top: 25px;
        padding-top: 25px;
        border-top: 1px solid #e2e8f0;
    }
    
    .auth-footer p {
        margin: 0;
        color: #718096;
        font-size: 14px;
    }
    
    .link-primary {
        color: #06b6d4;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .link-primary:hover {
        color: #0ea5e9;
        text-decoration: underline;
    }
    
    .auth-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }
    
    .water-drop {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float 20s infinite ease-in-out;
    }
    
    .drop-1 {
        width: 80px;
        height: 80px;
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }
    
    .drop-2 {
        width: 60px;
        height: 60px;
        top: 70%;
        left: 80%;
        animation-delay: 5s;
    }
    
    .drop-3 {
        width: 100px;
        height: 100px;
        top: 40%;
        left: 5%;
        animation-delay: 10s;
    }
    
    .drop-4 {
        width: 70px;
        height: 70px;
        top: 20%;
        right: 10%;
        animation-delay: 15s;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
            opacity: 0.3;
        }
        50% {
            transform: translateY(-30px) rotate(180deg);
            opacity: 0.6;
        }
    }
    
    @media (max-width: 640px) {
        .auth-card {
            padding: 30px 20px;
        }
        
        .auth-icon {
            width: 60px;
            height: 60px;
            font-size: 28px;
        }
        
        .auth-header h2 {
            font-size: 24px;
        }
    }
</style>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(inputId + '-eye');
        
        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.remove('fa-eye');
            eye.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            eye.classList.remove('fa-eye-slash');
            eye.classList.add('fa-eye');
        }
    }
    
    // Password strength indicator
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strength = document.getElementById('password-strength');
        
        if (password.length === 0) {
            strength.className = 'password-strength';
        } else if (password.length < 8) {
            strength.className = 'password-strength weak';
        } else if (password.length < 12 || !/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
            strength.className = 'password-strength medium';
        } else {
            strength.className = 'password-strength strong';
        }
        
        checkPasswordMatch();
    });
    
    // Password match indicator
    document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);
    
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const matchIndicator = document.getElementById('password-match');
        
        if (confirmPassword.length === 0) {
            matchIndicator.textContent = '';
            matchIndicator.className = 'password-match';
        } else if (password === confirmPassword) {
            matchIndicator.textContent = '✓ Passwords match';
            matchIndicator.className = 'password-match match';
        } else {
            matchIndicator.textContent = '✗ Passwords do not match';
            matchIndicator.className = 'password-match no-match';
        }
    }
    
    // Form validation
    document.getElementById('register-form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match. Please check and try again.');
            return false;
        }
    });
</script>

<?php
$content = ob_get_clean();
$title = 'Register - Peepit';
include __DIR__ . '/../layouts/frontend.php';
?>
