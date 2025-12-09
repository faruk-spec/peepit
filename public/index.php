<?php

// Start session with security settings
ini_set('session.cookie_httponly', 1);
// Only set secure cookie if HTTPS is available
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}
ini_set('session.use_strict_mode', 1);
session_start();

// Check if installer exists and database is not configured
if (is_dir(__DIR__ . '/../install') && !file_exists(__DIR__ . '/../config/database.php')) {
    header('Location: ../install/index.php');
    exit;
}

// Check if vendor/autoload exists
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    die('<h1>Composer Dependencies Missing</h1><p>Please run: <code>composer install</code> or <code>composer dump-autoload</code></p><p>This error means the PHP autoloader needs to be generated.</p><hr><p><strong>Quick Fix:</strong></p><pre>cd /www/wwwroot/peepit.mymultibranch.com\ncomposer dump-autoload</pre>');
}

// Autoload
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/helpers/functions.php';

// Force HTTPS
if (config('force_https') && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on')) {
    $redirectUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirectUrl);
    exit;
}

use App\Core\Router;

$router = new Router();

// Public routes
$router->get('/', function() {
    require_once __DIR__ . '/../app/controllers/HomeController.php';
    $controller = new \App\Controllers\HomeController();
    $controller->index();
});

$router->get('/register', function() {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    $controller = new \App\Controllers\AuthController();
    $controller->register();
});

$router->post('/register', function() {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    $controller = new \App\Controllers\AuthController();
    $controller->doRegister();
});

$router->get('/login', function() {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    $controller = new \App\Controllers\AuthController();
    $controller->login();
});

$router->post('/login', function() {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    $controller = new \App\Controllers\AuthController();
    $controller->doLogin();
});

$router->get('/logout', function() {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    $controller = new \App\Controllers\AuthController();
    $controller->logout();
});

// Frontend order flow routes
$router->get('/order/start', function() {
    require_login();
    require_once __DIR__ . '/../app/controllers/OrderController.php';
    $controller = new \App\Controllers\OrderController();
    $controller->step1();
});

$router->post('/order/step2', function() {
    require_login();
    require_once __DIR__ . '/../app/controllers/OrderController.php';
    $controller = new \App\Controllers\OrderController();
    $controller->step2();
});

$router->post('/order/step3', function() {
    require_login();
    require_once __DIR__ . '/../app/controllers/OrderController.php';
    $controller = new \App\Controllers\OrderController();
    $controller->step3();
});

$router->post('/order/step4', function() {
    require_login();
    require_once __DIR__ . '/../app/controllers/OrderController.php';
    $controller = new \App\Controllers\OrderController();
    $controller->step4();
});

$router->post('/order/step5', function() {
    require_login();
    require_once __DIR__ . '/../app/controllers/OrderController.php';
    $controller = new \App\Controllers\OrderController();
    $controller->step5();
});

$router->post('/order/step6', function() {
    require_login();
    require_once __DIR__ . '/../app/controllers/OrderController.php';
    $controller = new \App\Controllers\OrderController();
    $controller->step6();
});

$router->post('/order/step7', function() {
    require_login();
    require_once __DIR__ . '/../app/controllers/OrderController.php';
    $controller = new \App\Controllers\OrderController();
    $controller->step7();
});

$router->post('/order/submit', function() {
    require_login();
    require_once __DIR__ . '/../app/controllers/OrderController.php';
    $controller = new \App\Controllers\OrderController();
    $controller->submit();
});

$router->get('/my-orders', function() {
    require_login();
    require_once __DIR__ . '/../app/controllers/MyOrdersController.php';
    $controller = new \App\Controllers\MyOrdersController();
    $controller->index();
});

$router->get('/order/{id}', function($id) {
    require_login();
    require_once __DIR__ . '/../app/controllers/MyOrdersController.php';
    $controller = new \App\Controllers\MyOrdersController();
    $controller->view($id);
});

$router->get('/profile', function() {
    require_login();
    require_once __DIR__ . '/../app/controllers/ProfileController.php';
    $controller = new \App\Controllers\ProfileController();
    $controller->index();
});

$router->post('/profile/update', function() {
    require_login();
    require_once __DIR__ . '/../app/controllers/ProfileController.php';
    $controller = new \App\Controllers\ProfileController();
    $controller->update();
});

// Admin routes
$router->get('/admin', function() {
    require_role('sales');
    require_once __DIR__ . '/../app/controllers/admin/DashboardController.php';
    $controller = new \App\Controllers\Admin\DashboardController();
    $controller->index();
});

$router->get('/admin/login', function() {
    require_once __DIR__ . '/../app/controllers/admin/AdminAuthController.php';
    $controller = new \App\Controllers\Admin\AdminAuthController();
    $controller->login();
});

$router->post('/admin/login', function() {
    require_once __DIR__ . '/../app/controllers/admin/AdminAuthController.php';
    $controller = new \App\Controllers\Admin\AdminAuthController();
    $controller->doLogin();
});

// Admin Bottle Management
$router->get('/admin/bottles', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/BottleController.php';
    $controller = new \App\Controllers\Admin\BottleController();
    $controller->index();
});

$router->get('/admin/bottles/create', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/BottleController.php';
    $controller = new \App\Controllers\Admin\BottleController();
    $controller->create();
});

$router->post('/admin/bottles/store', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/BottleController.php';
    $controller = new \App\Controllers\Admin\BottleController();
    $controller->store();
});

$router->get('/admin/bottles/edit/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/BottleController.php';
    $controller = new \App\Controllers\Admin\BottleController();
    $controller->edit($id);
});

$router->post('/admin/bottles/update/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/BottleController.php';
    $controller = new \App\Controllers\Admin\BottleController();
    $controller->update($id);
});

$router->post('/admin/bottles/delete/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/BottleController.php';
    $controller = new \App\Controllers\Admin\BottleController();
    $controller->delete($id);
});

// Admin Size Management
$router->get('/admin/sizes', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SizeController.php';
    $controller = new \App\Controllers\Admin\SizeController();
    $controller->index();
});

$router->get('/admin/sizes/create', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SizeController.php';
    $controller = new \App\Controllers\Admin\SizeController();
    $controller->create();
});

$router->post('/admin/sizes/store', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SizeController.php';
    $controller = new \App\Controllers\Admin\SizeController();
    $controller->store();
});

$router->get('/admin/sizes/edit/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SizeController.php';
    $controller = new \App\Controllers\Admin\SizeController();
    $controller->edit($id);
});

$router->post('/admin/sizes/update/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SizeController.php';
    $controller = new \App\Controllers\Admin\SizeController();
    $controller->update($id);
});

$router->post('/admin/sizes/delete/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SizeController.php';
    $controller = new \App\Controllers\Admin\SizeController();
    $controller->delete($id);
});

// Admin Color Management
$router->get('/admin/colors', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/ColorController.php';
    $controller = new \App\Controllers\Admin\ColorController();
    $controller->index();
});

$router->get('/admin/colors/create', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/ColorController.php';
    $controller = new \App\Controllers\Admin\ColorController();
    $controller->create();
});

$router->post('/admin/colors/store', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/ColorController.php';
    $controller = new \App\Controllers\Admin\ColorController();
    $controller->store();
});

$router->get('/admin/colors/edit/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/ColorController.php';
    $controller = new \App\Controllers\Admin\ColorController();
    $controller->edit($id);
});

$router->post('/admin/colors/update/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/ColorController.php';
    $controller = new \App\Controllers\Admin\ColorController();
    $controller->update($id);
});

$router->post('/admin/colors/delete/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/ColorController.php';
    $controller = new \App\Controllers\Admin\ColorController();
    $controller->delete($id);
});

// Admin Order Management
$router->get('/admin/orders', function() {
    require_role('sales');
    require_once __DIR__ . '/../app/controllers/admin/OrderManagementController.php';
    $controller = new \App\Controllers\Admin\OrderManagementController();
    $controller->index();
});

$router->get('/admin/orders/{id}', function($id) {
    require_role('sales');
    require_once __DIR__ . '/../app/controllers/admin/OrderManagementController.php';
    $controller = new \App\Controllers\Admin\OrderManagementController();
    $controller->viewOrder($id);
});

// Keep old route for backward compatibility
$router->get('/admin/orders/view/{id}', function($id) {
    require_role('sales');
    require_once __DIR__ . '/../app/controllers/admin/OrderManagementController.php';
    $controller = new \App\Controllers\Admin\OrderManagementController();
    $controller->viewOrder($id);
});

$router->post('/admin/orders/update-status/{id}', function($id) {
    require_role('sales');
    require_once __DIR__ . '/../app/controllers/admin/OrderManagementController.php';
    $controller = new \App\Controllers\Admin\OrderManagementController();
    $controller->updateStatus($id);
});

// Admin Template Management
$router->get('/admin/templates', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/TemplateController.php';
    $controller = new \App\Controllers\Admin\TemplateController();
    $controller->index();
});

$router->get('/admin/templates/create', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/TemplateController.php';
    $controller = new \App\Controllers\Admin\TemplateController();
    $controller->create();
});

$router->post('/admin/templates/store', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/TemplateController.php';
    $controller = new \App\Controllers\Admin\TemplateController();
    $controller->store();
});

$router->get('/admin/templates/edit/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/TemplateController.php';
    $controller = new \App\Controllers\Admin\TemplateController();
    $controller->edit($id);
});

$router->post('/admin/templates/update/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/TemplateController.php';
    $controller = new \App\Controllers\Admin\TemplateController();
    $controller->update($id);
});

$router->post('/admin/templates/delete/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/TemplateController.php';
    $controller = new \App\Controllers\Admin\TemplateController();
    $controller->delete($id);
});

// Admin User Management
$router->get('/admin/users', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/UserController.php';
    $controller = new \App\Controllers\Admin\UserController();
    $controller->index();
});

$router->get('/admin/users/edit/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/UserController.php';
    $controller = new \App\Controllers\Admin\UserController();
    $controller->edit($id);
});

$router->post('/admin/users/update/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/UserController.php';
    $controller = new \App\Controllers\Admin\UserController();
    $controller->update($id);
});

$router->post('/admin/users/delete/{id}', function($id) {
    require_role('superadmin');
    require_once __DIR__ . '/../app/controllers/admin/UserController.php';
    $controller = new \App\Controllers\Admin\UserController();
    $controller->delete($id);
});

// Admin Email Logs
$router->get('/admin/email-logs', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/EmailLogController.php';
    $controller = new \App\Controllers\Admin\EmailLogController();
    $controller->index();
});

$router->get('/admin/email-logs/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/EmailLogController.php';
    $controller = new \App\Controllers\Admin\EmailLogController();
    $controller->viewLog($id);
});

// Keep old route for backward compatibility
$router->get('/admin/email-logs/view/{id}', function($id) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/EmailLogController.php';
    $controller = new \App\Controllers\Admin\EmailLogController();
    $controller->viewLog($id);
});

$router->post('/admin/email-logs/delete/{id}', function($id) {
    require_role('superadmin');
    require_once __DIR__ . '/../app/controllers/admin/EmailLogController.php';
    $controller = new \App\Controllers\Admin\EmailLogController();
    $controller->delete($id);
});

$router->post('/admin/email-logs/clear', function() {
    require_role('superadmin');
    require_once __DIR__ . '/../app/controllers/admin/EmailLogController.php';
    $controller = new \App\Controllers\Admin\EmailLogController();
    $controller->clear();
});

// Admin Analytics
$router->get('/admin/analytics', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/AnalyticsController.php';
    $controller = new \App\Controllers\Admin\AnalyticsController();
    $controller->index();
});

$router->get('/admin/analytics/export', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/AnalyticsController.php';
    $controller = new \App\Controllers\Admin\AnalyticsController();
    $controller->export();
});

// Admin Bulk Operations
$router->get('/admin/bulk-operations', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/BulkOperationsController.php';
    $controller = new \App\Controllers\Admin\BulkOperationsController();
    $controller->index();
});

$router->post('/admin/bulk-operations/import-products', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/BulkOperationsController.php';
    $controller = new \App\Controllers\Admin\BulkOperationsController();
    $controller->importProducts();
});

$router->get('/admin/bulk-operations/export-products', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/BulkOperationsController.php';
    $controller = new \App\Controllers\Admin\BulkOperationsController();
    $controller->exportProducts();
});

$router->get('/admin/bulk-operations/export-orders', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/BulkOperationsController.php';
    $controller = new \App\Controllers\Admin\BulkOperationsController();
    $controller->exportOrders();
});

$router->get('/admin/bulk-operations/export-customers', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/BulkOperationsController.php';
    $controller = new \App\Controllers\Admin\BulkOperationsController();
    $controller->exportCustomers();
});

// Admin Notifications
$router->get('/admin/notifications', function() {
    require_role('sales');
    require_once __DIR__ . '/../app/controllers/admin/NotificationsController.php';
    $controller = new \App\Controllers\Admin\NotificationsController();
    $controller->index();
});

$router->post('/admin/notifications/mark-as-read', function() {
    require_role('sales');
    require_once __DIR__ . '/../app/controllers/admin/NotificationsController.php';
    $controller = new \App\Controllers\Admin\NotificationsController();
    $controller->markAsRead();
});

$router->post('/admin/notifications/mark-all-read', function() {
    require_role('sales');
    require_once __DIR__ . '/../app/controllers/admin/NotificationsController.php';
    $controller = new \App\Controllers\Admin\NotificationsController();
    $controller->markAllAsRead();
});

$router->post('/admin/notifications/delete', function() {
    require_role('sales');
    require_once __DIR__ . '/../app/controllers/admin/NotificationsController.php';
    $controller = new \App\Controllers\Admin\NotificationsController();
    $controller->delete();
});

$router->get('/admin/notifications/unread-count', function() {
    require_role('sales');
    require_once __DIR__ . '/../app/controllers/admin/NotificationsController.php';
    $controller = new \App\Controllers\Admin\NotificationsController();
    $controller->getUnreadCount();
});

// Admin Settings
$router->get('/admin/settings', function() {
    require_role('superadmin');
    require_once __DIR__ . '/../app/controllers/admin/SettingsController.php';
    $controller = new \App\Controllers\Admin\SettingsController();
    $controller->index();
});

$router->post('/admin/settings/update', function() {
    require_role('superadmin');
    require_once __DIR__ . '/../app/controllers/admin/SettingsController.php';
    $controller = new \App\Controllers\Admin\SettingsController();
    $controller->update();
});

// Admin System Tools
$router->get('/admin/system-tools', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SystemToolsController.php';
    $controller = new \App\Controllers\Admin\SystemToolsController();
    $controller->index();
});

$router->get('/admin/system-tools/cache', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SystemToolsController.php';
    $controller = new \App\Controllers\Admin\SystemToolsController();
    $controller->cache();
});

$router->post('/admin/system-tools/cache/clear', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SystemToolsController.php';
    $controller = new \App\Controllers\Admin\SystemToolsController();
    $controller->clearCache();
});

$router->get('/admin/system-tools/logs', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SystemToolsController.php';
    $controller = new \App\Controllers\Admin\SystemToolsController();
    $controller->logs();
});

$router->post('/admin/system-tools/logs/clear', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SystemToolsController.php';
    $controller = new \App\Controllers\Admin\SystemToolsController();
    $controller->clearLogs();
});

$router->get('/admin/system-tools/backup', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SystemToolsController.php';
    $controller = new \App\Controllers\Admin\SystemToolsController();
    $controller->backup();
});

$router->post('/admin/system-tools/backup/create', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SystemToolsController.php';
    $controller = new \App\Controllers\Admin\SystemToolsController();
    $controller->createBackup();
});

$router->get('/admin/system-tools/backup/download/{filename}', function($filename) {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SystemToolsController.php';
    $controller = new \App\Controllers\Admin\SystemToolsController();
    $controller->downloadBackup($filename);
});

$router->post('/admin/system-tools/backup/delete', function() {
    require_role('manager');
    require_once __DIR__ . '/../app/controllers/admin/SystemToolsController.php';
    $controller = new \App\Controllers\Admin\SystemToolsController();
    $controller->deleteBackup();
});

$router->get('/admin/settings/smtp', function() {
    require_role('superadmin');
    require_once __DIR__ . '/../app/controllers/admin/SettingsController.php';
    $controller = new \App\Controllers\Admin\SettingsController();
    $controller->smtp();
});

$router->post('/admin/settings/smtp/update', function() {
    require_role('superadmin');
    require_once __DIR__ . '/../app/controllers/admin/SettingsController.php';
    $controller = new \App\Controllers\Admin\SettingsController();
    $controller->updateSmtp();
});

// Get current URL and method
$url = $_GET['url'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'];

// Normalize URL - ensure it starts with /
if ($url !== '/' && strpos($url, '/') !== 0) {
    $url = '/' . $url;
}

// Dispatch router
try {
    $router->dispatch($url, $method);
} catch (Exception $e) {
    http_response_code(500);
    echo 'Application Error: ' . $e->getMessage();
}
