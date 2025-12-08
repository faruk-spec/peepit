<?php ob_start(); ?>

<div class="hero">
    <div class="container">
        <h1>Welcome to Peepit</h1>
        <p>Create Your Perfect Custom Water Bottle</p>
        <?php if (is_logged_in()): ?>
            <a href="<?= url('order/start') ?>" class="btn btn-success">Start Ordering</a>
        <?php else: ?>
            <a href="<?= url('register') ?>" class="btn btn-success">Get Started</a>
            <a href="<?= url('login') ?>" class="btn btn-secondary">Login</a>
        <?php endif; ?>
    </div>
</div>

<div class="container" style="margin-top: 60px;">
    <div class="text-center mb-20">
        <h2>How It Works</h2>
        <p>Simple steps to get your custom water bottle</p>
    </div>
    
    <div class="grid grid-4">
        <div class="card text-center">
            <div style="font-size: 48px; margin-bottom: 20px;">📱</div>
            <h3>1. Register</h3>
            <p>Create your free account in seconds</p>
        </div>
        <div class="card text-center">
            <div style="font-size: 48px; margin-bottom: 20px;">🎨</div>
            <h3>2. Design</h3>
            <p>Choose model, size, color & label</p>
        </div>
        <div class="card text-center">
            <div style="font-size: 48px; margin-bottom: 20px;">🛒</div>
            <h3>3. Order</h3>
            <p>Select quantity & place order</p>
        </div>
        <div class="card text-center">
            <div style="font-size: 48px; margin-bottom: 20px;">🚚</div>
            <h3>4. Delivery</h3>
            <p>Get it delivered to your door</p>
        </div>
    </div>

    <?php if (!empty($bottle_models)): ?>
        <div style="margin-top: 60px;">
            <h2 class="text-center mb-20">Our Bottle Models</h2>
            <div class="grid grid-3">
                <?php foreach ($bottle_models as $model): ?>
                    <div class="card">
                        <?php if ($model['image']): ?>
                            <img src="<?= url('uploads/bottles/' . escape($model['image'])) ?>" alt="<?= escape($model['name']) ?>" style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px; margin-bottom: 15px;">
                        <?php else: ?>
                            <div style="width: 100%; height: 200px; background: var(--light); border-radius: 5px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; font-size: 48px;">🚰</div>
                        <?php endif; ?>
                        <h3><?= escape($model['name']) ?></h3>
                        <p><?= escape($model['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div style="margin: 60px 0;">
        <h2 class="text-center mb-20">Features</h2>
        <div class="grid grid-3">
            <div class="card">
                <h3>🎨 Custom Colors</h3>
                <p>Choose from preset colors or use our color picker to create your perfect shade</p>
            </div>
            <div class="card">
                <h3>🏷️ Label Designer</h3>
                <p>Upload your own design or use our templates with drag & drop editor</p>
            </div>
            <div class="card">
                <h3>📏 Multiple Sizes</h3>
                <p>Available in 250ml, 500ml, 1L, 2L, and 5L capacities</p>
            </div>
            <div class="card">
                <h3>💰 Bulk Pricing</h3>
                <p>Get better prices when you order more bottles</p>
            </div>
            <div class="card">
                <h3>🚚 Fast Delivery</h3>
                <p>Quick turnaround time with reliable delivery</p>
            </div>
            <div class="card">
                <h3>✅ Quality Assured</h3>
                <p>Premium quality materials and printing</p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Peepit - Custom Water Bottles';
include __DIR__ . '/../layouts/frontend.php';
?>
