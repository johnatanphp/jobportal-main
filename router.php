<?php
// PHP built-in server router for CodeIgniter
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly if they exist
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false; // serve the file as-is
}

// Also serve files from document root
if ($uri !== '/' && file_exists(__DIR__ . $uri) && !is_dir(__DIR__ . $uri)) {
    return false;
}

// All other requests go through CodeIgniter's index.php
require_once __DIR__ . '/index.php';
