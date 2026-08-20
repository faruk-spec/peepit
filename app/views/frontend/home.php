<?php ob_start(); ?>

<!-- Hero Section - Full Width with Advanced Water Effects -->
<div class="hero full-width water-effect">
    <div class="water-droplets"></div>
    <div class="wave-layer wave1"></div>
    <div class="wave-layer wave2"></div>
    <div class="wave-layer wave3"></div>
    <div class="container hero-content">
        <h1 class="animate-fade-in"><i class="fas fa-tint"></i> Welcome to Peepit</h1>
        <p class="animate-fade-in-delay">Create Your Perfect Custom Water Bottle</p>
        <?php if (is_logged_in()): ?>
            <a href="<?= url('order/start') ?>" class="btn btn-success animate-fade-in-delay-2"><i class="fas fa-shopping-cart"></i> Start Ordering</a>
        <?php else: ?>
            <a href="<?= url('register') ?>" class="btn btn-success animate-fade-in-delay-2"><i class="fas fa-user-plus"></i> Get Started</a>
            <a href="<?= url('login') ?>" class="btn btn-secondary animate-fade-in-delay-2"><i class="fas fa-sign-in-alt"></i> Login</a>
        <?php endif; ?>
    </div>
</div>

<style>
.hero {
    position: relative;
    overflow: hidden;
}

.hero-content {
    position: relative;
    z-index: 10;
}

/* Water Droplets Animation */
.water-droplets {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.water-droplets::before,
.water-droplets::after {
    content: '';
    position: absolute;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
    animation: droplet 8s ease-in-out infinite;
}

.water-droplets::before {
    top: 10%;
    left: 20%;
    animation-delay: 0s;
}

.water-droplets::after {
    top: 60%;
    right: 15%;
    animation-delay: 4s;
}

@keyframes droplet {
    0%, 100% {
        transform: translateY(0) scale(1);
        opacity: 0;
    }
    50% {
        transform: translateY(30px) scale(1.2);
        opacity: 1;
    }
}

/* Animated Wave Layers */
.wave-layer {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 200%;
    height: 100px;
    background-repeat: repeat-x;
    animation: wave 20s linear infinite;
}

.wave1 {
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100"><path fill="%23ffffff" fill-opacity="0.1" d="M0,50 C240,30 480,70 720,50 C960,30 1200,70 1440,50 L1440,100 L0,100 Z"></path></svg>') repeat-x;
    animation-duration: 25s;
    opacity: 0.4;
    z-index: 2;
}

.wave2 {
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100"><path fill="%23ffffff" fill-opacity="0.1" d="M0,60 C240,80 480,40 720,60 C960,80 1200,40 1440,60 L1440,100 L0,100 Z"></path></svg>') repeat-x;
    animation-duration: 20s;
    animation-direction: reverse;
    opacity: 0.3;
    z-index: 3;
}

.wave3 {
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100"><path fill="%23ffffff" fill-opacity="0.15" d="M0,70 C240,50 480,90 720,70 C960,50 1200,90 1440,70 L1440,100 L0,100 Z"></path></svg>') repeat-x;
    animation-duration: 15s;
    opacity: 0.5;
    z-index: 4;
}

@keyframes wave {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* Fade In Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeInUp 1s ease-out;
}

.animate-fade-in-delay {
    animation: fadeInUp 1s ease-out 0.3s both;
}

.animate-fade-in-delay-2 {
    animation: fadeInUp 1s ease-out 0.6s both;
}
</style>

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

<!-- Statistics Section -->
<section class="section-light">
    <div class="container">
        <h2 class="text-center mb-40">Trusted by Thousands</h2>
        <div class="stats-counter grid grid-4">
            <div class="counter-item card glass-card text-center">
                <div class="counter-icon"><i class="fas fa-users"></i></div>
                <div class="counter-value" data-target="5000">0</div>
                <div class="counter-label">Happy Customers</div>
            </div>
            <div class="counter-item card glass-card text-center">
                <div class="counter-icon"><i class="fas fa-wine-bottle"></i></div>
                <div class="counter-value" data-target="50000">0</div>
                <div class="counter-label">Bottles Delivered</div>
            </div>
            <div class="counter-item card glass-card text-center">
                <div class="counter-icon"><i class="fas fa-palette"></i></div>
                <div class="counter-value" data-target="100">0</div>
                <div class="counter-label">Design Options</div>
            </div>
            <div class="counter-item card glass-card text-center">
                <div class="counter-icon"><i class="fas fa-star"></i></div>
                <div class="counter-value" data-target="98">0</div>
                <div class="counter-label">% Satisfaction Rate</div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section>
    <div class="container">
        <h2 class="text-center mb-40">What Our Customers Say</h2>
        <div class="testimonials grid grid-3">
            <div class="testimonial-card card glass-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"Excellent quality and amazing customization options! Our company ordered 500 bottles and they turned out perfect. The design tool was so easy to use."</p>
                <div class="testimonial-author">
                    <div class="author-avatar">R</div>
                    <div class="author-info">
                        <div class="author-name">Rahul Sharma</div>
                        <div class="author-company">Tech Startup CEO</div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card card glass-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"Fast delivery and great customer service. We used these bottles for our wedding favors and everyone loved them! The color matching was perfect."</p>
                <div class="testimonial-author">
                    <div class="author-avatar">P</div>
                    <div class="author-info">
                        <div class="author-name">Priya Patel</div>
                        <div class="author-company">Event Organizer</div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card card glass-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"Best custom bottle service in India! The pricing is competitive and the quality is top-notch. We've been ordering from Peepit for over a year now."</p>
                <div class="testimonial-author">
                    <div class="author-avatar">A</div>
                    <div class="author-info">
                        <div class="author-name">Amit Kumar</div>
                        <div class="author-company">Fitness Center Owner</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Stats Counter */
    .stats-counter {
        margin-top: 40px;
    }

    .counter-item {
        padding: 40px 20px;
    }

    .counter-icon {
        font-size: 48px;
        color: var(--primary);
        margin-bottom: 20px;
    }

    .counter-value {
        font-size: 48px;
        font-weight: bold;
        color: var(--dark);
        margin-bottom: 10px;
    }

    .counter-label {
        font-size: 16px;
        color: var(--text-light);
        font-weight: 500;
    }

    /* Testimonials */
    .testimonials {
        margin-top: 40px;
    }

    .testimonial-card {
        padding: 30px;
    }

    .testimonial-rating {
        color: #F59E0B;
        font-size: 18px;
        margin-bottom: 20px;
    }

    .testimonial-text {
        font-size: 16px;
        line-height: 1.8;
        color: var(--text);
        margin-bottom: 20px;
        font-style: italic;
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 15px;
        padding-top: 20px;
        border-top: 2px solid var(--border);
    }

    .author-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--gradient-primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
    }

    .author-name {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 3px;
    }

    .author-company {
        font-size: 13px;
        color: var(--text-light);
    }

    @media (max-width: 768px) {
        .counter-value {
            font-size: 36px;
        }

        .testimonials {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    // Counter Animation
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-target'));
        const hasPercent = element.parentElement.textContent.includes('%');
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                element.textContent = target.toLocaleString();
                if (hasPercent) {
                    element.textContent += '%';
                }
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString();
            }
        }, 16);
    }

    // Trigger counter animation when section is visible
    const counters = document.querySelectorAll('.counter-value');
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                entry.target.classList.add('animated');
                animateCounter(entry.target);
            }
        });
    }, observerOptions);

    counters.forEach(counter => observer.observe(counter));
</script>

<?php
$content = ob_get_clean();
$title = 'Peepit - Custom Water Bottles';
include __DIR__ . '/../layouts/frontend.php';
?>
