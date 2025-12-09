<?php

/**
 * Security Helper Functions
 */

function sanitize($input)
{
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function escape($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function csrf_field()
{
    $token = $_SESSION['csrf_token'] ?? '';
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function csrf_token()
{
    return $_SESSION['csrf_token'] ?? '';
}

function validate_csrf()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            die('CSRF validation failed');
        }
    }
}

function generate_csrf()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * URL Helper Functions
 */

function url($path = '')
{
    $config = require __DIR__ . '/../config/app.php';
    $baseUrl = rtrim($config['app_url'], '/');
    $path = ltrim($path, '/');
    return $baseUrl . '/' . $path;
}

function redirect($url)
{
    header("Location: {$url}");
    exit;
}

function back()
{
    $referer = $_SERVER['HTTP_REFERER'] ?? url();
    redirect($referer);
}

/**
 * Session Helper Functions
 */

function session($key, $default = null)
{
    return $_SESSION[$key] ?? $default;
}

function set_session($key, $value)
{
    $_SESSION[$key] = $value;
}

function flash($key, $message = null)
{
    if ($message === null) {
        $value = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $value;
    }
    $_SESSION['flash'][$key] = $message;
}

function old($key, $default = '')
{
    return $_SESSION['old'][$key] ?? $default;
}

function set_old($data)
{
    $_SESSION['old'] = $data;
}

function clear_old()
{
    unset($_SESSION['old']);
}

/**
 * Authentication Helper Functions
 */

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function user_id()
{
    return $_SESSION['user_id'] ?? null;
}

function user_role()
{
    return $_SESSION['user_role'] ?? 'user';
}

function has_role($role)
{
    $userRole = user_role();
    
    $roleHierarchy = [
        'superadmin' => ['superadmin', 'manager', 'sales', 'webmail', 'user'],
        'manager' => ['manager', 'sales', 'user'],
        'sales' => ['sales', 'user'],
        'webmail' => ['webmail', 'user'],
        'user' => ['user']
    ];
    
    return in_array($role, $roleHierarchy[$userRole] ?? ['user']);
}

function require_login()
{
    if (!is_logged_in()) {
        redirect(url('login'));
    }
}

function require_role($role)
{
    require_login();
    if (!has_role($role)) {
        http_response_code(403);
        die('Access denied');
    }
}

/**
 * File Upload Helper Functions
 */

function validate_upload($file, $maxSize = 5242880, $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'])
{
    if (!isset($file['error']) || is_array($file['error'])) {
        return 'Invalid file upload';
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return 'Upload error: ' . $file['error'];
    }

    if ($file['size'] > $maxSize) {
        return 'File too large. Maximum size: ' . ($maxSize / 1048576) . 'MB';
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!in_array($mimeType, $allowedTypes)) {
        return 'Invalid file type';
    }

    return true;
}

function upload_file($file, $destination)
{
    $validation = validate_upload($file);
    if ($validation !== true) {
        return ['error' => $validation];
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $targetPath = $destination . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['error' => 'Failed to move uploaded file'];
    }

    return ['success' => true, 'filename' => $filename, 'path' => $targetPath];
}

/**
 * Utility Helper Functions
 */

function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

function config($key, $default = null)
{
    static $config = null;
    
    if ($config === null) {
        $configFile = __DIR__ . '/../config/app.php';
        if (!file_exists($configFile)) {
            // Return default if config file doesn't exist (installation not complete)
            return $default;
        }
        $config = require $configFile;
    }
    
    return $config[$key] ?? $default;
}

function format_date($date, $format = 'Y-m-d H:i:s')
{
    return date($format, strtotime($date));
}

function currency_format($amount)
{
    $currency = config('currency', '₹');
    return $currency . number_format($amount, 2);
}

function get_client_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'];
}

function get_user_agent()
{
    return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
}

function get_device_type()
{
    $userAgent = get_user_agent();
    if (preg_match('/mobile/i', $userAgent)) {
        return 'Mobile';
    } elseif (preg_match('/tablet/i', $userAgent)) {
        return 'Tablet';
    }
    return 'Desktop';
}
