<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Peepit - Custom Water Bottles' ?></title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
    <?= $head ?? '' ?>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="<?= url() ?>">🚰 Peepit</a>
            </div>
            <ul class="navbar-menu">
                <li><a href="<?= url() ?>">Home</a></li>
                <li><a href="<?= url('order/start') ?>">Order Now</a></li>
                <?php if (is_logged_in()): ?>
                    <li><a href="<?= url('my-orders') ?>">My Orders</a></li>
                    <li class="dropdown">
                        <a href="#"><?= escape(current_user()['name']) ?> ▼</a>
                        <ul class="dropdown-menu">
                            <li><a href="<?= url('profile') ?>">Profile</a></li>
                            <?php if (has_role('sales')): ?>
                                <li><a href="<?= url('admin') ?>">Admin Panel</a></li>
                            <?php endif; ?>
                            <li><a href="<?= url('logout') ?>">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="<?= url('login') ?>">Login</a></li>
                    <li><a href="<?= url('register') ?>" class="btn-primary">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <?php if (flash('success')): ?>
        <div class="alert alert-success"><?= escape(flash('success')) ?></div>
    <?php endif; ?>

    <?php if (flash('error')): ?>
        <div class="alert alert-error"><?= escape(flash('error')) ?></div>
    <?php endif; ?>

    <main>
        <?= $content ?? '' ?>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Peepit</h3>
                    <p>Custom water bottle ordering made easy. Design your perfect bottle today!</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?= url() ?>">Home</a></li>
                        <li><a href="<?= url('order/start') ?>">Order Now</a></li>
                        <li><a href="<?= url('contact') ?>">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <p>
                        <a href="https://wa.me/<?= str_replace('+', '', config('whatsapp_number')) ?>" target="_blank" class="contact-btn whatsapp">
                            WhatsApp
                        </a>
                    </p>
                    <p>
                        <a href="mailto:<?= config('contact_email') ?>" class="contact-btn email">
                            Email Us
                        </a>
                    </p>
                    <p>
                        <a href="tel:<?= config('contact_phone') ?>" class="contact-btn phone">
                            Call Us
                        </a>
                    </p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Peepit. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="<?= url('js/app.js') ?>"></script>
    <?= $scripts ?? '' ?>
</body>
</html>
