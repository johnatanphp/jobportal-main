<?php
// PHP built-in server router for CodeIgniter
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Check if the file exists in the public directory
$public_file = __DIR__ . '/public' . $uri;
if ($uri !== '/' && file_exists($public_file) && !is_dir($public_file)) {
    return false; // serve the file as-is
}

// Check if the file exists in the root directory
$root_file = __DIR__ . $uri;
if ($uri !== '/' && file_exists($root_file) && !is_dir($root_file)) {
    return false; // serve the file as-is
}

// All other requests go through CodeIgniter's index.php
require_once __DIR__ . '/index.php';
