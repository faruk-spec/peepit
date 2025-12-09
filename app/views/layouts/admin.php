<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel - Peepit' ?></title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Admin Dashboard Styles */
        .admin-layout {
            display: flex;
            min-height: 100vh;
            background: #F8FAFC;
        }

        .admin-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1E293B 0%, #0F172A 100%);
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .admin-logo {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 10px;
            background: rgba(14, 165, 233, 0.1);
        }

        .admin-logo h2 {
            color: white;
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .admin-logo a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-logo i {
            font-size: 28px;
            color: #0EA5E9;
        }

        .admin-menu {
            list-style: none;
            padding: 15px 10px;
            margin: 0;
        }

        .admin-menu li {
            margin: 3px 0;
        }

        .admin-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 8px;
            font-size: 14.5px;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .admin-menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--primary);
            transform: scaleY(0);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-menu a:hover {
            background: rgba(14, 165, 233, 0.15);
            color: white;
            transform: translateX(3px);
        }

        .admin-menu a.active {
            background: rgba(14, 165, 233, 0.2);
            color: white;
            font-weight: 600;
        }

        .admin-menu a.active::before {
            transform: scaleY(1);
        }

        .admin-menu i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .menu-section {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .menu-section:first-of-type {
            margin-top: 10px;
            padding-top: 0;
            border-top: none;
        }

        .menu-section-title {
            padding: 8px 15px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.35);
            letter-spacing: 1.2px;
            margin-bottom: 5px;
        }

        .admin-content {
            flex: 1;
            margin-left: 280px;
            background: #F8FAFC;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #E2E8F0;
        }

        .admin-header h1 {
            margin: 0;
            font-size: 26px;
            color: #1E293B;
            font-weight: 700;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 8px 15px;
            border-radius: 12px;
            background: #F8FAFC;
            transition: all 0.3s;
        }

        .admin-user:hover {
            background: #F1F5F9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .admin-user-info {
            text-align: right;
        }

        .admin-user-name {
            font-weight: 600;
            color: #1E293B;
            font-size: 14px;
        }

        .admin-user-role {
            font-size: 12px;
            color: #64748B;
            text-transform: capitalize;
        }

        .admin-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0EA5E9 0%, #06B6D4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(14, 165, 233, 0.3);
        }

        .admin-main {
            padding: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #E2E8F0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary);
            transition: height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .stat-card.success::before {
            background: var(--success);
        }

        .stat-card.warning::before {
            background: var(--warning);
        }

        .stat-card.error::before {
            background: var(--error);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 18px;
        }

        .stat-title {
            font-size: 13px;
            color: #64748B;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-card .stat-icon {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(14, 165, 233, 0.05) 100%);
            color: var(--primary);
        }

        .stat-card.success .stat-icon {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
            color: var(--success);
        }

        .stat-card.warning .stat-icon {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);
            color: var(--warning);
        }

        .stat-card.error .stat-icon {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
            color: var(--error);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: #1E293B;
            margin-bottom: 8px;
            line-height: 1;
        }

        .stat-trend {
            font-size: 13px;
            color: var(--success);
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
        }

        .stat-trend i {
            font-size: 12px;
        }

        .admin-table {
            width: 100%;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #E2E8F0;
        }

        .admin-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            background: linear-gradient(180deg, #1E293B 0%, #0F172A 100%);
            color: white;
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .admin-table td {
            padding: 16px 20px;
            border-bottom: 1px solid #E2E8F0;
            font-size: 14px;
            color: #475569;
        }

        .admin-table tr:last-child td {
            border-bottom: none;
        }

        .admin-table tr:hover {
            background: #F8FAFC;
        }

        /* Dropdown Styles */
        .dropdown-menu {
            list-style: none;
            padding-left: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            margin: 8px 10px;
            pointer-events: none;
            opacity: 0;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
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
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.65);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 8px;
            font-size: 14.5px;
            font-weight: 500;
            position: relative;
        }

        .dropdown-toggle:hover {
            background: rgba(14, 165, 233, 0.15);
            color: white;
            transform: translateX(3px);
        }

        .dropdown-toggle::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown.open .dropdown-toggle {
            background: rgba(14, 165, 233, 0.15);
            color: white;
        }

        .dropdown.open .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        .dropdown-menu a {
            padding: 10px 15px 10px 50px !important;
            color: rgba(255, 255, 255, 0.75);
            display: flex;
            align-items: center;
            pointer-events: auto;
            border-radius: 6px;
            margin: 2px 8px;
            font-size: 13.5px;
        }
        
        .dropdown-menu a:hover {
            color: white;
            background: rgba(14, 165, 233, 0.2);
            transform: translateX(2px);
        }

        .dropdown-menu a.active {
            color: white;
            background: rgba(14, 165, 233, 0.25);
            font-weight: 600;
        }

        /* Notification Badge */
        #notification-badge {
            margin-left: auto;
            background: #EF4444;
            color: white;
            padding: 2px 7px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
        }

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: flex !important;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 16px;
            }

            .admin-main {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .admin-header {
                padding: 15px 20px;
            }

            .admin-header h1 {
                font-size: 20px;
            }

            .admin-user-info {
                display: none;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .mobile-menu-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0EA5E9 0%, #06B6D4 100%);
            color: white;
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(14, 165, 233, 0.3);
            transition: all 0.3s;
        }

        .mobile-menu-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(14, 165, 233, 0.4);
        }

        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
    </style>
    <?= $head ?? '' ?>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        
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
                <li>
                    <a href="<?= url('admin/home-content') ?>" class="<?= ($current_page ?? '') === 'home-content' ? 'active' : '' ?>">
                        <i class="fas fa-home"></i>
                        <span>Homepage Content</span>
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
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth <= 1024 && 
                sidebar.classList.contains('open') && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target)) {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            }
        });

        // Dropdown toggle functionality
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const dropdown = this.parentElement;
                const section = this.dataset.section;
                
                // Close other dropdowns
                document.querySelectorAll('.dropdown').forEach(d => {
                    if (d !== dropdown && d.classList.contains('open')) {
                        d.classList.remove('open');
                    }
                });
                
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
            // Restore dropdown states
            document.querySelectorAll('.dropdown').forEach(dropdown => {
                const toggle = dropdown.querySelector('.dropdown-toggle');
                const section = toggle?.dataset.section;
                
                if (section && localStorage.getItem('dropdown_' + section) === 'open') {
                    dropdown.classList.add('open');
                }
            });

            // Auto-open dropdown if current page is inside it
            document.querySelectorAll('.dropdown-menu a.active').forEach(activeLink => {
                const dropdown = activeLink.closest('.dropdown');
                if (dropdown) {
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
