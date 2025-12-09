<?php ob_start(); ?>

<!-- Hero Section - Full Width -->
<div class="hero full-width">
    <div class="container">
        <h1><i class="fas fa-tint"></i> Welcome to Peepit</h1>
        <p>Create Your Perfect Custom Water Bottle</p>
        <?php if (is_logged_in()): ?>
            <a href="<?= url('order/start') ?>" class="btn btn-success"><i class="fas fa-shopping-cart"></i> Start Ordering</a>
        <?php else: ?>
            <a href="<?= url('register') ?>" class="btn btn-success"><i class="fas fa-user-plus"></i> Get Started</a>
            <a href="<?= url('login') ?>" class="btn btn-secondary"><i class="fas fa-sign-in-alt"></i> Login</a>
        <?php endif; ?>
    </div>
</div>

<!-- How It Works Section -->
<section class="wave-bg">
    <div class="container">
        <div class="text-center mb-40">
            <h2>How It Works</h2>
            <p>Simple steps to get your custom water bottle</p>
        </div>
        
        <div class="grid grid-4">
            <div class="card text-center glass-card">
                <div class="feature-icon"><i class="fas fa-user-plus"></i></div>
                <h3>1. Register</h3>
                <p>Create your free account in seconds</p>
            </div>
            <div class="card text-center glass-card">
                <div class="feature-icon"><i class="fas fa-palette"></i></div>
                <h3>2. Design</h3>
                <p>Choose model, size, color & label</p>
            </div>
            <div class="card text-center glass-card">
                <div class="feature-icon"><i class="fas fa-shopping-bag"></i></div>
                <h3>3. Order</h3>
                <p>Select quantity & place order</p>
            </div>
            <div class="card text-center glass-card">
                <div class="feature-icon"><i class="fas fa-truck"></i></div>
                <h3>4. Delivery</h3>
                <p>Get it delivered to your door</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section>
    <div class="container">
        <h2 class="text-center mb-40">Why Choose Peepit?</h2>
        <div class="grid grid-3">
            <div class="card">
                <div class="feature-icon"><i class="fas fa-fill-drip"></i></div>
                <h3>Custom Colors</h3>
                <p>Choose from preset colors or use our color picker to create your perfect shade</p>
            </div>
            <div class="card">
                <div class="feature-icon"><i class="fas fa-tag"></i></div>
                <h3>Label Designer</h3>
                <p>Upload your own design or use our templates with drag & drop editor</p>
            </div>
            <div class="card">
                <div class="feature-icon"><i class="fas fa-ruler-combined"></i></div>
                <h3>Multiple Sizes</h3>
                <p>Available in 250ml, 500ml, 1L, 2L, and 5L capacities</p>
            </div>
            <div class="card">
                <div class="feature-icon"><i class="fas fa-dollar-sign"></i></div>
                <h3>Bulk Pricing</h3>
                <p>Get better prices when you order more bottles</p>
            </div>
            <div class="card">
                <div class="feature-icon"><i class="fas fa-shipping-fast"></i></div>
                <h3>Fast Delivery</h3>
                <p>Quick turnaround time with reliable delivery</p>
            </div>
            <div class="card">
                <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                <h3>Quality Assured</h3>
                <p>Premium quality materials and printing</p>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($bottle_models)): ?>
<!-- Bottle Models Section -->
<section class="section-light">
    <div class="container">
        <h2 class="text-center mb-40">Our Bottle Models</h2>
        <div class="grid grid-3">
            <?php foreach ($bottle_models as $model): ?>
                <div class="card">
                    <?php if ($model['image']): ?>
                        <img src="<?= url('uploads/bottles/' . escape($model['image'])) ?>" alt="<?= escape($model['name']) ?>" style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; margin-bottom: 15px;">
                    <?php else: ?>
                        <div style="width: 100%; height: 200px; background: var(--light); border-radius: 12px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; font-size: 64px; color: var(--primary);">
                            <i class="fas fa-wine-bottle"></i>
                        </div>
                    <?php endif; ?>
                    <h3><?= escape($model['name']) ?></h3>
                    <p><?= escape($model['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<div class="cta-section">
    <div class="container">
        <h2><i class="fas fa-rocket"></i> Ready to Create Your Custom Bottle?</h2>
        <p>Join thousands of satisfied customers who trust Peepit for their custom water bottle needs</p>
        <?php if (is_logged_in()): ?>
            <a href="<?= url('order/start') ?>" class="btn btn-success btn-lg"><i class="fas fa-play-circle"></i> Start Your Order Now</a>
        <?php else: ?>
            <a href="<?= url('register') ?>" class="btn btn-success btn-lg"><i class="fas fa-arrow-right"></i> Get Started Free</a>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Peepit - Custom Water Bottles';
include __DIR__ . '/../layouts/frontend.php';
?>
