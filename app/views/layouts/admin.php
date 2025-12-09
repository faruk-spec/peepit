<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel - Peepit' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 70px;
            --primary-color: #0d6efd;
            --sidebar-bg: #1a2035;
            --sidebar-hover: #242b42;
            --text-muted: #8897ad;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f0f2f5;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1030;
            transition: all 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-wrapper::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-wrapper::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-wrapper::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 20px 20px;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 12px;
        }

        .sidebar-header .logo-text h3 {
            color: white;
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .sidebar-header .logo-text p {
            color: var(--text-muted);
            margin: 0;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Sidebar Menu */
        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-title {
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 20px 10px 20px;
            margin-top: 10px;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            position: relative;
        }

        .sidebar-menu .nav-link:hover {
            background: var(--sidebar-hover);
            color: white;
            border-left-color: var(--primary-color);
        }

        .sidebar-menu .nav-link.active {
            background: linear-gradient(90deg, rgba(13, 110, 253, 0.1) 0%, transparent 100%);
            color: white;
            border-left-color: var(--primary-color);
        }

        .sidebar-menu .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
            text-align: center;
        }

        .sidebar-menu .nav-link .menu-arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-menu .has-submenu.active > .nav-link .menu-arrow {
            transform: rotate(90deg);
        }

        .sidebar-menu .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0, 0, 0, 0.2);
        }

        .sidebar-menu .has-submenu.active > .submenu {
            max-height: 1000px;
        }

        .sidebar-menu .submenu a {
            padding-left: 52px;
            font-size: 14px;
        }

        /* Header */
        .page-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .top-header {
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1020;
            display: flex;
            align-items: center;
            padding: 0 30px;
        }

        .topbar-nav {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .mobile-toggle-menu {
            display: none;
            background: transparent;
            border: none;
            font-size: 24px;
            color: #333;
            cursor: pointer;
            margin-right: 15px;
        }

        .search-bar {
            position: relative;
            max-width: 400px;
            width: 100%;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 20px 10px 40px;
            border: 1px solid #e0e6ed;
            border-radius: 30px;
            background: #f8f9fa;
            font-size: 14px;
        }

        .search-bar input:focus {
            outline: none;
            background: white;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .search-bar i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .user-box {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notifications-dropdown {
            position: relative;
        }

        .notifications-dropdown .btn {
            background: transparent;
            border: none;
            font-size: 20px;
            color: #333;
            position: relative;
            padding: 8px;
        }

        .notifications-dropdown .badge {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 9px;
            padding: 3px 5px;
        }

        .user-info {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .user-info .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
            margin-right: 10px;
            border: 2px solid #e0e6ed;
        }

        .user-info .user-details h6 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .user-info .user-details p {
            margin: 0;
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Main Content */
        .page-content {
            padding: 30px;
            min-height: calc(100vh - var(--header-height));
        }

        .page-breadcrumb {
            margin-bottom: 25px;
        }

        .page-breadcrumb h4 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 5px 0 0 0;
        }

        .breadcrumb-item {
            font-size: 13px;
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0e6ed;
            height: 100%;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .stats-card .card-body {
            padding: 0;
        }

        .stats-card .d-flex {
            align-items: flex-start;
        }

        .stats-card .widget-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stats-card .widget-icon.bg-gradient-purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stats-card .widget-icon.bg-gradient-success {
            background: linear-gradient(135deg, #2dce89 0%, #2dcecc 100%);
            color: white;
        }

        .stats-card .widget-icon.bg-gradient-warning {
            background: linear-gradient(135deg, #ffd93d 0%, #ff8008 100%);
            color: white;
        }

        .stats-card .widget-icon.bg-gradient-danger {
            background: linear-gradient(135deg, #f56036 0%, #f093fb 100%);
            color: white;
        }

        .stats-card .widget-info {
            flex: 1;
            margin-left: 15px;
        }

        .stats-card .widget-info p {
            margin: 0;
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .stats-card .widget-info h3 {
            margin: 5px 0;
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }

        .stats-card .widget-info .progress-info {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .stats-card .widget-info .progress-info span {
            font-size: 12px;
            color: #28a745;
            font-weight: 500;
        }

        .stats-card .widget-info .progress-info i {
            font-size: 10px;
        }

        /* Card Styles */
        .card {
            border-radius: 12px;
            border: 1px solid #e0e6ed;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e0e6ed;
            padding: 20px 25px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .card-body {
            padding: 25px;
        }

        /* Table Styles */
        .table {
            margin: 0;
        }

        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #e0e6ed;
            color: #333;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 15px;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e0e6ed;
            font-size: 14px;
            color: #555;
        }

        /* Badge Styles */
        .badge {
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 30px;
        }

        /* Button Styles */
        .btn {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar-wrapper {
                left: calc(var(--sidebar-width) * -1);
            }

            .sidebar-wrapper.toggled {
                left: 0;
            }

            .page-wrapper {
                margin-left: 0;
            }

            .mobile-toggle-menu {
                display: block;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1029;
            }

            .overlay.show {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .search-bar {
                display: none;
            }

            .user-info .user-details {
                display: none;
            }

            .page-content {
                padding: 20px 15px;
            }
        }
    </style>
    <?= $head ?? '' ?>
</head>
<body>
    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <div class="sidebar-wrapper" id="sidebarWrapper">
        <div class="sidebar-header">
            <div class="logo-icon">
                <i class="fas fa-wine-bottle"></i>
            </div>
            <div class="logo-text">
                <h3>Peepit</h3>
                <p>Admin Panel</p>
            </div>
        </div>

        <div class="sidebar-menu">
            <div class="menu-title">MAIN</div>
            <ul>
                <li>
                    <a href="<?= url('admin') ?>" class="nav-link <?= ($current_page ?? '') === 'dashboard' ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>

            <div class="menu-title">ORDER MANAGEMENT</div>
            <ul>
                <li>
                    <a href="<?= url('admin/orders') ?>" class="nav-link <?= ($current_page ?? '') === 'orders' ? 'active' : '' ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </li>
            </ul>

            <div class="menu-title">CATALOG MANAGEMENT</div>
            <ul>
                <li class="has-submenu <?= in_array($current_page ?? '', ['bottles', 'sizes', 'colors', 'templates', 'pricing']) ? 'active' : '' ?>">
                    <a href="javascript:;" class="nav-link">
                        <i class="fas fa-box"></i>
                        <span>Catalog</span>
                        <i class="fas fa-chevron-right menu-arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="<?= url('admin/bottles') ?>" class="nav-link <?= ($current_page ?? '') === 'bottles' ? 'active' : '' ?>">
                            <i class="fas fa-wine-bottle"></i> Bottle Models
                        </a></li>
                        <li><a href="<?= url('admin/sizes') ?>" class="nav-link <?= ($current_page ?? '') === 'sizes' ? 'active' : '' ?>">
                            <i class="fas fa-ruler-vertical"></i> Bottle Sizes
                        </a></li>
                        <li><a href="<?= url('admin/colors') ?>" class="nav-link <?= ($current_page ?? '') === 'colors' ? 'active' : '' ?>">
                            <i class="fas fa-palette"></i> Color Presets
                        </a></li>
                        <li><a href="<?= url('admin/templates') ?>" class="nav-link <?= ($current_page ?? '') === 'templates' ? 'active' : '' ?>">
                            <i class="fas fa-tags"></i> Label Templates
                        </a></li>
                        <li><a href="<?= url('admin/pricing') ?>" class="nav-link <?= ($current_page ?? '') === 'pricing' ? 'active' : '' ?>">
                            <i class="fas fa-dollar-sign"></i> Price Setup
                        </a></li>
                        <li><a href="<?= url('admin/pricing/bottle-models') ?>" class="nav-link <?= ($current_page ?? '') === 'bottle-model-pricing' ? 'active' : '' ?>">
                            <i class="fas fa-link"></i> Bottle Model Pricing
                        </a></li>
                    </ul>
                </li>
            </ul>

            <div class="menu-title">TRAFFIC TRACKING</div>
            <ul>
                <li class="has-submenu">
                    <a href="javascript:;" class="nav-link">
                        <i class="fas fa-chart-area"></i>
                        <span>Traffic Analytics</span>
                        <i class="fas fa-chevron-right menu-arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="<?= url('admin/traffic') ?>" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                        <li><a href="<?= url('admin/traffic/realtime') ?>" class="nav-link"><i class="fas fa-bolt"></i> Real-Time Visitors</a></li>
                        <li><a href="<?= url('admin/traffic/sources') ?>" class="nav-link"><i class="fas fa-link"></i> Traffic Sources</a></li>
                        <li><a href="<?= url('admin/traffic/geo') ?>" class="nav-link"><i class="fas fa-globe"></i> Geo-Location</a></li>
                        <li><a href="<?= url('admin/traffic/devices') ?>" class="nav-link"><i class="fas fa-laptop"></i> Devices & Browsers</a></li>
                        <li><a href="<?= url('admin/traffic/behavior') ?>" class="nav-link"><i class="fas fa-user-check"></i> User Behavior</a></li>
                        <li><a href="<?= url('admin/traffic/campaigns') ?>" class="nav-link"><i class="fas fa-bullhorn"></i> UTM Campaigns</a></li>
                        <li><a href="<?= url('admin/traffic/reports') ?>" class="nav-link"><i class="fas fa-file-alt"></i> Reports & Export</a></li>
                        <li><a href="<?= url('admin/traffic/alerts') ?>" class="nav-link"><i class="fas fa-bell"></i> Alerts</a></li>
                        <li><a href="<?= url('admin/traffic/bots') ?>" class="nav-link"><i class="fas fa-robot"></i> Bot Detection</a></li>
                        <li><a href="<?= url('admin/traffic/heatmaps') ?>" class="nav-link"><i class="fas fa-fire"></i> Heatmaps</a></li>
                        <li><a href="<?= url('admin/traffic/conversions') ?>" class="nav-link"><i class="fas fa-bullseye"></i> Conversions</a></li>
                        <li><a href="<?= url('admin/traffic/retention') ?>" class="nav-link"><i class="fas fa-chart-line"></i> Retention</a></li>
                    </ul>
                </li>
            </ul>

            <div class="menu-title">USER MANAGEMENT</div>
            <ul>
                <li>
                    <a href="<?= url('admin/users') ?>" class="nav-link <?= ($current_page ?? '') === 'users' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
            </ul>

            <div class="menu-title">CONTENT MANAGEMENT</div>
            <ul>
                <li><a href="<?= url('admin/pages') ?>" class="nav-link <?= ($current_page ?? '') === 'pages' ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i> Pages
                </a></li>
                <li><a href="<?= url('admin/navigation') ?>" class="nav-link <?= ($current_page ?? '') === 'navigation' ? 'active' : '' ?>">
                    <i class="fas fa-bars"></i> Navigation
                </a></li>
                <li><a href="<?= url('admin/hero-slider') ?>" class="nav-link <?= ($current_page ?? '') === 'hero-slider' ? 'active' : '' ?>">
                    <i class="fas fa-images"></i> Hero Slider
                </a></li>
                <li><a href="<?= url('admin/home-content') ?>" class="nav-link <?= ($current_page ?? '') === 'home-content' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Homepage Content
                </a></li>
            </ul>

            <div class="menu-title">SYSTEM</div>
            <ul>
                <li><a href="<?= url('admin/email-logs') ?>" class="nav-link <?= ($current_page ?? '') === 'email-logs' ? 'active' : '' ?>">
                    <i class="fas fa-envelope"></i> Email Logs
                </a></li>
                <li><a href="<?= url('admin/analytics') ?>" class="nav-link <?= ($current_page ?? '') === 'analytics' ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i> Analytics
                </a></li>
                <li><a href="<?= url('admin/notifications') ?>" class="nav-link <?= ($current_page ?? '') === 'notifications' ? 'active' : '' ?>">
                    <i class="fas fa-bell"></i> Notifications
                    <span id="notification-badge" class="badge bg-danger ms-auto" style="display: none;"></span>
                </a></li>
                <li><a href="<?= url('admin/bulk-operations') ?>" class="nav-link <?= ($current_page ?? '') === 'bulk-operations' ? 'active' : '' ?>">
                    <i class="fas fa-file-import"></i> Bulk Operations
                </a></li>
                <li><a href="<?= url('admin/settings') ?>" class="nav-link <?= ($current_page ?? '') === 'settings' ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i> Settings
                </a></li>
                <li><a href="<?= url('admin/system-tools') ?>" class="nav-link <?= ($current_page ?? '') === 'system-tools' ? 'active' : '' ?>">
                    <i class="fas fa-wrench"></i> System Tools
                </a></li>
            </ul>

            <div class="menu-title">QUICK LINKS</div>
            <ul>
                <li><a href="<?= url() ?>" class="nav-link">
                    <i class="fas fa-globe"></i> View Website
                </a></li>
                <li><a href="<?= url('logout') ?>" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a></li>
            </ul>
        </div>
    </div>

    <!-- Main Page Wrapper -->
    <div class="page-wrapper">
        <!-- Top Header -->
        <header class="top-header">
            <nav class="topbar-nav">
                <button class="mobile-toggle-menu" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search anything..." class="form-control">
                </div>

                <div class="user-box">
                    <div class="notifications-dropdown">
                        <button class="btn" type="button">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger" id="notification-badge-header" style="display: none;">0</span>
                        </button>
                    </div>

                    <div class="user-info">
                        <div class="user-avatar">
                            <?= strtoupper(substr(current_user()['name'] ?? 'A', 0, 1)) ?>
                        </div>
                        <div class="user-details">
                            <h6><?= escape(current_user()['name'] ?? 'Admin') ?></h6>
                            <p><?= ucfirst(escape(current_user()['role'] ?? 'admin')) ?></p>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Page Content -->
        <div class="page-content">
            <?php if (has_flash()): ?>
                <?php $flash_type = flash_type(); ?>
                <?php $flash_message = get_flash(); ?>
                <div class="alert alert-<?= $flash_type === 'error' ? 'danger' : $flash_type ?> alert-dismissible fade show" role="alert">
                    <?php if ($flash_type === 'success'): ?>
                        <i class="fas fa-check-circle me-2"></i>
                    <?php elseif ($flash_type === 'error'): ?>
                        <i class="fas fa-exclamation-circle me-2"></i>
                    <?php elseif ($flash_type === 'warning'): ?>
                        <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php else: ?>
                        <i class="fas fa-info-circle me-2"></i>
                    <?php endif; ?>
                    <?= escape($flash_message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $content ?? '' ?>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="<?= url('js/app.js') ?>"></script>
    
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebarWrapper');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('toggled');
            overlay.classList.toggle('show');
        }

        // Submenu Toggle
        document.querySelectorAll('.has-submenu > .nav-link').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.closest('.has-submenu');
                parent.classList.toggle('active');
            });
        });

        // Update notification badge
        function updateNotificationBadge() {
            fetch('/admin/notifications/unread-count')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    const badgeHeader = document.getElementById('notification-badge-header');
                    if (badge && badgeHeader) {
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'inline';
                            badgeHeader.textContent = data.count;
                            badgeHeader.style.display = 'inline';
                        } else {
                            badge.style.display = 'none';
                            badgeHeader.style.display = 'none';
                        }
                    }
                })
                .catch(err => console.log('Failed to fetch notification count'));
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationBadge();
            setInterval(updateNotificationBadge, 30000);
        });
    </script>
    <?= $scripts ?? '' ?>
</body>
</html>
