<?php ob_start(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <h1 class="mb-30"><i class="fas fa-user-circle"></i> My Profile</h1>

    <div class="profile-grid">
        <!-- Profile Information -->
        <div class="card glass-card">
            <h2 class="mb-20"><i class="fas fa-user-edit"></i> Profile Information</h2>
            
            <form method="POST" action="<?= url('profile/update') ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="name" class="required">Full Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="form-control" 
                           value="<?= escape($user['name']) ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" 
                           id="email" 
                           class="form-control" 
                           value="<?= escape($user['email']) ?>" 
                           disabled>
                    <small class="form-text">Email cannot be changed</small>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" 
                           name="phone" 
                           id="phone" 
                           class="form-control" 
                           value="<?= escape($user['phone'] ?? '') ?>" 
                           placeholder="+91 XXXXX XXXXX">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="card glass-card">
            <h2 class="mb-20"><i class="fas fa-lock"></i> Change Password</h2>
            
            <form method="POST" action="<?= url('profile/update') ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" 
                           name="current_password" 
                           id="current_password" 
                           class="form-control" 
                           placeholder="Enter current password">
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" 
                           name="new_password" 
                           id="new_password" 
                           class="form-control" 
                           placeholder="Enter new password"
                           minlength="8">
                    <small class="form-text">Minimum 8 characters</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" 
                           name="confirm_password" 
                           id="confirm_password" 
                           class="form-control" 
                           placeholder="Confirm new password">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> Update Password
                </button>
            </form>
        </div>

        <!-- Account Information -->
        <div class="card">
            <h2 class="mb-20"><i class="fas fa-info-circle"></i> Account Information</h2>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Account Status</div>
                    <div class="info-value">
                        <span class="badge badge-success">
                            <i class="fas fa-check-circle"></i> Active
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Member Since</div>
                    <div class="info-value">
                        <?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Role</div>
                    <div class="info-value">
                        <?= ucfirst(escape($user['role'])) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <h2 class="mb-20"><i class="fas fa-bolt"></i> Quick Actions</h2>
            
            <div class="actions-grid">
                <a href="<?= url('my-orders') ?>" class="action-btn">
                    <i class="fas fa-list-alt"></i>
                    <span>My Orders</span>
                </a>
                <a href="<?= url('order/start') ?>" class="action-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>New Order</span>
                </a>
                <a href="<?= url('logout') ?>" class="action-btn logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }

    .profile-grid > .card:first-child,
    .profile-grid > .card:nth-child(2) {
        grid-column: span 1;
    }

    .required::after {
        content: '*';
        color: var(--error);
        margin-left: 5px;
    }

    .form-text {
        display: block;
        margin-top: 5px;
        font-size: 13px;
        color: var(--text-light);
    }

    .info-grid {
        display: grid;
        gap: 20px;
    }

    .info-item {
        padding: 15px;
        background: var(--light);
        border-radius: 8px;
        border-left: 4px solid var(--primary);
    }

    .info-label {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 5px;
        font-weight: 500;
    }

    .info-value {
        font-size: 16px;
        color: var(--dark);
        font-weight: 600;
    }

    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
    }

    .badge-success {
        background: #D1FAE5;
        color: #065F46;
    }

    .actions-grid {
        display: grid;
        gap: 15px;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 20px;
        background: var(--light);
        border: 2px solid var(--border);
        border-radius: 12px;
        text-decoration: none;
        color: var(--dark);
        font-weight: 600;
        transition: all 0.3s;
    }

    .action-btn:hover {
        border-color: var(--primary);
        background: white;
        transform: translateX(5px);
    }

    .action-btn.logout {
        border-color: var(--error);
        color: var(--error);
    }

    .action-btn.logout:hover {
        background: var(--error);
        color: white;
    }

    .action-btn i {
        font-size: 20px;
    }

    @media (max-width: 1024px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }

        .profile-grid > .card:first-child,
        .profile-grid > .card:nth-child(2) {
            grid-column: span 1;
        }
    }
</style>

<script>
    // Password confirmation validation
    document.getElementById('confirm_password').addEventListener('input', function() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = this.value;
        
        if (newPassword && confirmPassword && newPassword !== confirmPassword) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });
</script>

<?php
$content = ob_get_clean();
$title = 'My Profile - Peepit';
include __DIR__ . '/../layouts/frontend.php';
?>
