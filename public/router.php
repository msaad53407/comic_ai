<?php
// Router script for PHP built-in server
$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// If the file exists and is not a PHP file, serve it directly
if ($requestPath !== '/' && file_exists(__DIR__ . $requestPath) && !is_dir(__DIR__ . $requestPath)) {
    return false; // Serve the file as-is
}

// Otherwise, route everything through index.php                                                                                                                                                                                                                                                                                                                                        
require __DIR__ . '/index.php';

