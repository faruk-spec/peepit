<?php ob_start(); ?>

<div class="auth-container">
    <div class="auth-card glass-effect">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h2>Admin Panel</h2>
            <p>Enter your credentials to access the admin dashboard</p>
        </div>
        
        <?php if (has_flash()): ?>
            <div class="alert alert-<?= flash_type() ?>">
                <?= get_flash() ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= url('admin/login') ?>" id="admin-login-form">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-user-shield"></i> Admin Email
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control"
                       placeholder="admin@peepit.com"
                       required 
                       autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-key"></i> Password
                </label>
                <div class="password-input-wrapper">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control"
                           placeholder="Enter your password"
                           required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password-eye"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block water-drop-btn">
                <i class="fas fa-sign-in-alt"></i> Login to Admin Panel
            </button>
        </form>
        
        <div class="auth-footer">
            <a href="<?= url() ?>" class="link-secondary">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
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
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    nav, footer {
        display: none !important;
    }
    
    .auth-container {
        position: relative;
        width: 100%;
        max-width: 480px;
        padding: 20px;
        z-index: 10;
    }
    
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        animation: slideUp 0.6s ease-out;
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
        background: linear-gradient(135deg, #334155 0%, #1e293b 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: white;
        box-shadow: 0 10px 25px rgba(51, 65, 85, 0.5);
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 10px 25px rgba(51, 65, 85, 0.5);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 15px 35px rgba(51, 65, 85, 0.7);
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
        color: #334155;
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
        border-color: #334155;
        box-shadow: 0 0 0 3px rgba(51, 65, 85, 0.1);
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
        color: #334155;
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
        background: linear-gradient(135deg, #334155 0%, #1e293b 100%);
        border: none;
        transition: all 0.3s;
    }
    
    .water-drop-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(51, 65, 85, 0.5);
    }
    
    .water-drop-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
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
    
    .link-secondary {
        color: #64748b;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s;
        font-size: 14px;
    }
    
    .link-secondary:hover {
        color: #334155;
    }
    
    .link-secondary i {
        margin-right: 6px;
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
        background: rgba(255, 255, 255, 0.05);
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
</script>

<?php
$content = ob_get_clean();
$title = 'Admin Login - Peepit';
include __DIR__ . '/../layouts/frontend.php';
?>
