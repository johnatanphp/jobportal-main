<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
if (file_exists(__DIR__ . $path)) {
    return false;
}
$_SERVER["SCRIPT_NAME"] = "/index.php";
include_once "index.php";
