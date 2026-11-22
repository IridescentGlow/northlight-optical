<?php

// Router for `php artisan serve` (the Render deploy runs it). Laravel uses this file
// instead of its own resources/server.php when it exists. The changes from Laravel's:
// - the built-in server is handed real files only (is_file, not file_exists), so a folder
//   such as /storage/ or /build/ reaches Laravel and gets the site's 404 page and its
//   security headers instead of the built-in server's bare "Not Found" page;
// - Vite's build files are sent from here with a one-year cache (below).

$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && is_file($publicPath.$uri)) {
    // Files under /build/assets/ carry a hash of their contents in the name
    // (app-aaEF-fwy.css), so a changed file is a new address and the old one can be
    // kept for good. The built-in server sends static files with no cache headers at
    // all and ignores any set here before `return false`, so every visit downloaded
    // the CSS, the app script and the 300 KB model-viewer script again.
    if (str_starts_with($uri, '/build/assets/') && ! str_contains($uri, '..')) {
        northlightSendFile($publicPath.$uri, 'public, max-age=31536000, immutable');

        return true;
    }

    return false;
}

require_once $publicPath.'/index.php';

function northlightSendFile(string $path, string $cacheControl): void
{
    $types = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'text/javascript; charset=UTF-8',
        'mjs' => 'text/javascript; charset=UTF-8',
        'json' => 'application/json',
        'map' => 'application/json',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'avif' => 'image/avif',
        'gif' => 'image/gif',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'wasm' => 'application/wasm',
        'glb' => 'model/gltf-binary',
        'gltf' => 'model/gltf+json',
        'txt' => 'text/plain; charset=UTF-8',
        'xml' => 'application/xml',
    ];
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    header('Content-Type: '.($types[$extension] ?? 'application/octet-stream'));
    header('Content-Length: '.filesize($path));
    header('Cache-Control: '.$cacheControl);
    header('X-Content-Type-Options: nosniff');

    if ($_SERVER['REQUEST_METHOD'] !== 'HEAD') {
        readfile($path);
    }
}
