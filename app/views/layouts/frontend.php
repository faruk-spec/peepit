<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Peepit - Custom Water Bottles' ?></title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?= $head ?? '' ?>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="<?= url() ?>"><i class="fas fa-wine-bottle"></i> Peepit</a>
            </div>
            
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="navbar-menu" id="navbarMenu">
                <li><a href="<?= url() ?>"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="<?= url('order/start') ?>"><i class="fas fa-shopping-cart"></i> Order Now</a></li>
                <?php if (is_logged_in()): ?>
                    <li><a href="<?= url('dashboard') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="<?= url('my-orders') ?>"><i class="fas fa-list-alt"></i> My Orders</a></li>
                    <li class="dropdown">
                        <a href="#"><i class="fas fa-user-circle"></i> <?= escape(current_user()['name']) ?> <i class="fas fa-chevron-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?= url('profile') ?>"><i class="fas fa-user-edit"></i> Profile</a></li>
                            <?php if (has_role('sales')): ?>
                                <li><a href="<?= url('admin') ?>"><i class="fas fa-tachometer-alt"></i> Admin Panel</a></li>
                            <?php endif; ?>
                            <li><a href="<?= url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="<?= url('login') ?>"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li><a href="<?= url('register') ?>" class="btn-primary"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <?php if (flash('success')): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= escape(flash('success')) ?></div>
    <?php endif; ?>

    <?php if (flash('error')): ?>
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= escape(flash('error')) ?></div>
    <?php endif; ?>

    <main>
        <?= $content ?? '' ?>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><i class="fas fa-wine-bottle"></i> About Peepit</h3>
                    <p>Custom water bottle ordering made easy. Design your perfect bottle today with our professional customization tools and fast delivery service.</p>
                </div>
                <div class="footer-section">
                    <h3><i class="fas fa-link"></i> Quick Links</h3>
                    <ul>
                        <li><a href="<?= url() ?>"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="<?= url('order/start') ?>"><i class="fas fa-shopping-cart"></i> Order Now</a></li>
                        <li><a href="<?= url('my-orders') ?>"><i class="fas fa-list-alt"></i> My Orders</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3><i class="fas fa-phone-alt"></i> Contact Us</h3>
                    <p>
                        <a href="https://wa.me/<?= str_replace('+', '', config('whatsapp_number')) ?>" target="_blank" class="contact-btn whatsapp">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </p>
                    <p>
                        <a href="mailto:<?= config('contact_email') ?>" class="contact-btn email">
                            <i class="fas fa-envelope"></i> Email Us
                        </a>
                    </p>
                    <p>
                        <a href="tel:<?= config('contact_phone') ?>" class="contact-btn phone">
                            <i class="fas fa-phone"></i> Call Us
                        </a>
                    </p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Peepit. All rights reserved. <i class="fas fa-heart" style="color: var(--secondary);"></i></p>
            </div>
        </div>
    </footer>

    <script src="<?= url('js/app.js') ?>"></script>
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const navbarMenu = document.getElementById('navbarMenu');
            
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    navbarMenu.classList.toggle('active');
                    this.querySelector('i').classList.toggle('fa-bars');
                    this.querySelector('i').classList.toggle('fa-times');
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.navbar')) {
                        navbarMenu.classList.remove('active');
                        if (mobileMenuToggle.querySelector('i')) {
                            mobileMenuToggle.querySelector('i').classList.add('fa-bars');
                            mobileMenuToggle.querySelector('i').classList.remove('fa-times');
                        }
                    }
                });
            }
        });
    </script>
    <?= $scripts ?? '' ?>
</body>
</html>
