<?php
/**
 * Peepit - Root Index File
 * 
 * This file serves as the entry point when the DocumentRoot is pointing to the project root
 * instead of the public/ directory (Method B deployment).
 * 
 * For Method A (recommended for security): Configure DocumentRoot to point to public/ directory
 * For Method B (simpler setup): Keep DocumentRoot at root and use this file
 */

// Get the request URI and remove query string
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// If accessing the installer directory, serve it directly
if (strpos($requestUri, '/install/') === 0 || $requestUri === '/install') {
    $installerFile = __DIR__ . '/install/index.php';
    if (file_exists($installerFile)) {
        chdir(__DIR__ . '/install');
        require $installerFile;
        exit;
    }
}

// Check if the request is for a static file in the public directory
$publicPath = __DIR__ . '/public' . $requestUri;

// If it's a static file in the public directory, serve it directly
if (is_file($publicPath) && $requestUri !== '/index.php') {
    // Set appropriate content type
    $extension = pathinfo($publicPath, PATHINFO_EXTENSION);
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
        'otf' => 'font/otf',
    ];
    
    if (isset($mimeTypes[$extension])) {
        header('Content-Type: ' . $mimeTypes[$extension]);
    }
    
    readfile($publicPath);
    exit;
}

// For all other requests, change to public directory and load public/index.php
chdir(__DIR__ . '/public');
require __DIR__ . '/public/index.php';
