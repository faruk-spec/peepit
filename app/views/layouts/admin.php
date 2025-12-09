<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel - Peepit' ?></title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 260px;
            background: var(--dark);
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .admin-logo {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .admin-logo h2 {
            color: white;
            margin: 0;
            font-size: 24px;
        }

        .admin-logo a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .admin-menu li {
            margin: 5px 0;
        }

        .admin-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s;
        }

        .admin-menu a:hover,
        .admin-menu a.active {
            background: rgba(14, 165, 233, 0.2);
            color: white;
            border-left: 4px solid var(--primary);
        }

        .admin-menu i {
            width: 20px;
            text-align: center;
        }

        .menu-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .menu-section-title {
            padding: 8px 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 1px;
        }

        .admin-content {
            flex: 1;
            margin-left: 260px;
            background: var(--light);
            min-height: 100vh;
        }

        .admin-header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-header h1 {
            margin: 0;
            font-size: 24px;
            color: var(--dark);
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-user-info {
            text-align: right;
        }

        .admin-user-name {
            font-weight: 600;
            color: var(--dark);
        }

        .admin-user-role {
            font-size: 12px;
            color: var(--text-light);
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .admin-main {
            padding: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            border-left: 4px solid var(--primary);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-card.success {
            border-left-color: var(--success);
        }

        .stat-card.warning {
            border-left-color: var(--warning);
        }

        .stat-card.error {
            border-left-color: var(--error);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .stat-title {
            font-size: 14px;
            color: var(--text-light);
            font-weight: 500;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-card .stat-icon {
            background: rgba(14, 165, 233, 0.1);
            color: var(--primary);
        }

        .stat-card.success .stat-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stat-card.warning .stat-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .stat-card.error .stat-icon {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error);
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .stat-trend {
            font-size: 13px;
            color: var(--success);
        }

        .admin-table {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .admin-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            background: var(--dark);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .admin-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border);
        }

        .admin-table tr:last-child td {
            border-bottom: none;
        }

        .admin-table tr:hover {
            background: var(--light);
        }

        /* Dropdown Styles */
        .dropdown-menu {
            list-style: none;
            padding-left: 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            margin-top: 5px;
            margin-bottom: 5px;
            position: relative;
            pointer-events: none;
            opacity: 0;
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
        }

        .dropdown.open .dropdown-menu {
            max-height: 2000px;
            pointer-events: auto;
            opacity: 1;
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            transition: all 0.3s;
        }

        .dropdown-toggle::after {
            content: '▼';
            margin-left: auto;
            font-size: 10px;
            transition: transform 0.3s;
        }

        .dropdown.open .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        .dropdown-menu a {
            padding-left: 52px !important;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            pointer-events: auto;
            border-left: 3px solid transparent;
        }
        
        .dropdown-menu a:hover {
            color: white;
            background: rgba(14, 165, 233, 0.15);
            border-left: 3px solid var(--primary);
        }

        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
                z-index: 1000;
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: block !important;
            }
        }

        .mobile-menu-toggle {
            display: none;
            background: var(--primary);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
        }
    </style>
    <?= $head ?? '' ?>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-logo">
                <a href="<?= url('admin') ?>">
                    <i class="fas fa-wine-bottle"></i>
                    <h2>Peepit Admin</h2>
                </a>
            </div>

            <ul class="admin-menu">
                <!-- Main -->
                <li>
                    <a href="<?= url('admin') ?>" class="<?= ($current_page ?? '') === 'dashboard' ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Orders Section -->
                <li class="menu-section">
                    <div class="menu-section-title">Order Management</div>
                </li>
                <li>
                    <a href="<?= url('admin/orders') ?>" class="<?= ($current_page ?? '') === 'orders' ? 'active' : '' ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </li>

                <!-- Catalog Section -->
                <li class="menu-section">
                    <div class="menu-section-title">Catalog Management</div>
                </li>
                <li class="dropdown">
                    <div class="dropdown-toggle" data-section="catalog">
                        <i class="fas fa-box"></i>
                        <span>Catalog</span>
                    </div>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?= url('admin/bottles') ?>" class="<?= ($current_page ?? '') === 'bottles' ? 'active' : '' ?>">
                                <i class="fas fa-wine-bottle"></i>
                                <span>Bottle Models</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/sizes') ?>" class="<?= ($current_page ?? '') === 'sizes' ? 'active' : '' ?>">
                                <i class="fas fa-ruler-vertical"></i>
                                <span>Bottle Sizes</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/colors') ?>" class="<?= ($current_page ?? '') === 'colors' ? 'active' : '' ?>">
                                <i class="fas fa-palette"></i>
                                <span>Color Presets</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/templates') ?>" class="<?= ($current_page ?? '') === 'templates' ? 'active' : '' ?>">
                                <i class="fas fa-tags"></i>
                                <span>Label Templates</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/pricing') ?>" class="<?= ($current_page ?? '') === 'pricing' ? 'active' : '' ?>">
                                <i class="fas fa-dollar-sign"></i>
                                <span>Price Setup</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/pricing/bottle-models') ?>" class="<?= ($current_page ?? '') === 'bottle-model-pricing' ? 'active' : '' ?>">
                                <i class="fas fa-link"></i>
                                <span>Bottle Model Pricing</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Traffic Tracking Section -->
                <li class="menu-section">
                    <div class="menu-section-title">Traffic Tracking</div>
                </li>
                <li class="dropdown">
                    <div class="dropdown-toggle" data-section="traffic">
                        <i class="fas fa-chart-area"></i>
                        <span>Traffic Analytics</span>
                    </div>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?= url('admin/traffic') ?>" class="<?= ($current_page ?? '') === 'traffic' ? 'active' : '' ?>">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/traffic/realtime') ?>" class="<?= ($current_page ?? '') === 'traffic-realtime' ? 'active' : '' ?>">
                                <i class="fas fa-bolt"></i>
                                <span>Real-Time Visitors</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/traffic/sources') ?>" class="<?= ($current_page ?? '') === 'traffic-sources' ? 'active' : '' ?>">
                                <i class="fas fa-link"></i>
                                <span>Traffic Sources</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/traffic/geo') ?>" class="<?= ($current_page ?? '') === 'traffic-geo' ? 'active' : '' ?>">
                                <i class="fas fa-globe"></i>
                                <span>Geo-Location</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/traffic/devices') ?>" class="<?= ($current_page ?? '') === 'traffic-devices' ? 'active' : '' ?>">
                                <i class="fas fa-laptop"></i>
                                <span>Devices & Browsers</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/traffic/behavior') ?>" class="<?= ($current_page ?? '') === 'traffic-behavior' ? 'active' : '' ?>">
                                <i class="fas fa-user-check"></i>
                                <span>User Behavior</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/traffic/campaigns') ?>" class="<?= ($current_page ?? '') === 'traffic-campaigns' ? 'active' : '' ?>">
                                <i class="fas fa-bullhorn"></i>
                                <span>UTM Campaigns</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/traffic/reports') ?>" class="<?= ($current_page ?? '') === 'traffic-reports' ? 'active' : '' ?>">
                                <i class="fas fa-file-alt"></i>
                                <span>Reports & Export</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/traffic/alerts') ?>" class="<?= ($current_page ?? '') === 'traffic-alerts' ? 'active' : '' ?>">
                                <i class="fas fa-bell"></i>
                                <span>Alerts</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/traffic/bots') ?>" class="<?= ($current_page ?? '') === 'traffic-bots' ? 'active' : '' ?>">
                                <i class="fas fa-robot"></i>
                                <span>Bot Detection</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/traffic/heatmaps') ?>" class="<?= ($current_page ?? '') === 'traffic-heatmaps' ? 'active' : '' ?>">
                                <i class="fas fa-fire"></i>
                                <span>Heatmaps</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/traffic/conversions') ?>" class="<?= ($current_page ?? '') === 'traffic-conversions' ? 'active' : '' ?>">
                                <i class="fas fa-bullseye"></i>
                                <span>Conversions</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/traffic/retention') ?>" class="<?= ($current_page ?? '') === 'traffic-retention' ? 'active' : '' ?>">
                                <i class="fas fa-chart-line"></i>
                                <span>Retention</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- User Management Section -->
                <li class="menu-section">
                    <div class="menu-section-title">User Management</div>
                </li>
                <li>
                    <a href="<?= url('admin/users') ?>" class="<?= ($current_page ?? '') === 'users' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>

                <!-- CMS Section -->
                <li class="menu-section">
                    <div class="menu-section-title">Content Management</div>
                </li>
                <li>
                    <a href="<?= url('admin/pages') ?>" class="<?= ($current_page ?? '') === 'pages' ? 'active' : '' ?>">
                        <i class="fas fa-file-alt"></i>
                        <span>Pages</span>
                    </a>
                </li>
                <li>
                    <a href="<?= url('admin/navigation') ?>" class="<?= ($current_page ?? '') === 'navigation' ? 'active' : '' ?>">
                        <i class="fas fa-bars"></i>
                        <span>Navigation</span>
                    </a>
                </li>
                <li>
                    <a href="<?= url('admin/hero-slider') ?>" class="<?= ($current_page ?? '') === 'hero-slider' ? 'active' : '' ?>">
                        <i class="fas fa-images"></i>
                        <span>Hero Slider</span>
                    </a>
                </li>

                <!-- System Section -->
                <li class="menu-section">
                    <div class="menu-section-title">System</div>
                </li>
                <li>
                    <a href="<?= url('admin/email-logs') ?>" class="<?= ($current_page ?? '') === 'email-logs' ? 'active' : '' ?>">
                        <i class="fas fa-envelope"></i>
                        <span>Email Logs</span>
                    </a>
                </li>
                <li>
                    <a href="<?= url('admin/analytics') ?>" class="<?= ($current_page ?? '') === 'analytics' ? 'active' : '' ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li>
                    <a href="<?= url('admin/notifications') ?>" class="<?= ($current_page ?? '') === 'notifications' ? 'active' : '' ?>">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span id="notification-badge" class="badge badge-danger" style="display: none; margin-left: auto; background: #EF4444; color: white; padding: 2px 6px; border-radius: 10px; font-size: 11px;"></span>
                    </a>
                </li>
                <li>
                    <a href="<?= url('admin/bulk-operations') ?>" class="<?= ($current_page ?? '') === 'bulk-operations' ? 'active' : '' ?>">
                        <i class="fas fa-file-import"></i>
                        <span>Bulk Operations</span>
                    </a>
                </li>
                <li>
                    <a href="<?= url('admin/settings') ?>" class="<?= ($current_page ?? '') === 'settings' ? 'active' : '' ?>">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li>
                    <a href="<?= url('admin/system-tools') ?>" class="<?= ($current_page ?? '') === 'system-tools' ? 'active' : '' ?>">
                        <i class="fas fa-wrench"></i>
                        <span>System Tools</span>
                    </a>
                </li>

                <!-- Quick Links -->
                <li class="menu-section">
                    <div class="menu-section-title">Quick Links</div>
                </li>
                <li>
                    <a href="<?= url() ?>">
                        <i class="fas fa-home"></i>
                        <span>View Website</span>
                    </a>
                </li>
                <li>
                    <a href="<?= url('logout') ?>">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="admin-content">
            <!-- Header -->
            <header class="admin-header">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1><?= $page_title ?? 'Dashboard' ?></h1>
                </div>
                
                <div class="admin-user">
                    <div class="admin-user-info">
                        <div class="admin-user-name"><?= escape(current_user()['name'] ?? 'Admin') ?></div>
                        <div class="admin-user-role"><?= ucfirst(escape(current_user()['role'] ?? 'admin')) ?></div>
                    </div>
                    <div class="admin-avatar">
                        <?= strtoupper(substr(current_user()['name'] ?? 'A', 0, 1)) ?>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <?php if (has_flash()): ?>
                <?php $flash_type = flash_type(); ?>
                <?php $flash_message = get_flash(); ?>
                <div class="alert alert-<?= $flash_type ?>" style="margin: 20px 30px;">
                    <?php if ($flash_type === 'success'): ?>
                        <i class="fas fa-check-circle"></i>
                    <?php elseif ($flash_type === 'error'): ?>
                        <i class="fas fa-exclamation-circle"></i>
                    <?php elseif ($flash_type === 'warning'): ?>
                        <i class="fas fa-exclamation-triangle"></i>
                    <?php else: ?>
                        <i class="fas fa-info-circle"></i>
                    <?php endif; ?>
                    <?= escape($flash_message) ?>
                </div>
            <?php endif; ?>

            <!-- Main Content Area -->
            <main class="admin-main">
                <?= $content ?? '' ?>
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
        }

        // Dropdown toggle functionality
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const dropdown = this.parentElement;
                const section = this.dataset.section;
                
                dropdown.classList.toggle('open');
                
                if (dropdown.classList.contains('open')) {
                    localStorage.setItem('dropdown_' + section, 'open');
                } else {
                    localStorage.removeItem('dropdown_' + section);
                }
            });
        });

        // Restore dropdown states on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.dropdown').forEach(dropdown => {
                const toggle = dropdown.querySelector('.dropdown-toggle');
                const section = toggle?.dataset.section;
                
                if (section && localStorage.getItem('dropdown_' + section) === 'open') {
                    dropdown.classList.add('open');
                }
            });

            // Update notification badge
            fetch('/admin/notifications/unread-count')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline';
                    }
                })
                .catch(err => console.log('Failed to fetch notification count'));
        });

        // Auto-refresh notification badge every 30 seconds
        setInterval(function() {
            fetch('/admin/notifications/unread-count')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(err => console.log('Failed to fetch notification count'));
        }, 30000);
    </script>
    <script src="<?= url('js/app.js') ?>"></script>
    <?= $scripts ?? '' ?>
</body>
</html>
