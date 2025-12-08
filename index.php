<?php
/**
 * Peepit - Root Index File
 * 
 * This file serves as a fallback when the DocumentRoot is pointing to the project root
 * instead of the public/ directory. It redirects all requests to the public/ directory.
 * 
 * IMPORTANT: For production, configure your web server to point directly to the public/ directory.
 * This file is only a fallback for situations where DocumentRoot configuration cannot be changed.
 */

// Get the request URI
$requestUri = $_SERVER['REQUEST_URI'];

// If accessing the installer, redirect to install folder
if (strpos($requestUri, '/install') === 0 || $requestUri === '/install/') {
    header('Location: /install/index.php');
    exit;
}

// Check if the request is for a static file in the public directory
$publicPath = __DIR__ . '/public' . $requestUri;

// If it's a file in the public directory, serve it directly
if (is_file($publicPath)) {
    // Set appropriate content type
    $extension = pathinfo($publicPath, PATHINFO_EXTENSION);
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
    ];
    
    if (isset($mimeTypes[$extension])) {
        header('Content-Type: ' . $mimeTypes[$extension]);
    }
    
    readfile($publicPath);
    exit;
}

// For all other requests, include the public/index.php
chdir(__DIR__ . '/public');
require __DIR__ . '/public/index.php';
