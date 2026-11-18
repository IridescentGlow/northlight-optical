<?php

// Router for `php artisan serve` (the Render deploy runs it). Laravel uses this file
// instead of its own resources/server.php when it exists. The only change: the
// built-in server is handed real files only (is_file, not file_exists), so a folder
// such as /storage/ or /build/ reaches Laravel and gets the site's 404 page and its
// security headers instead of the built-in server's bare "Not Found" page.

$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && is_file($publicPath.$uri)) {
    return false;
}

require_once $publicPath.'/index.php';
