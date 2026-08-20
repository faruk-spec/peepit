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
