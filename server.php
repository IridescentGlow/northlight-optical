<?php

// Router for `php artisan serve` (the Render deploy runs it). Laravel uses this file
// instead of its own resources/server.php when it exists. The changes from Laravel's:
// - the built-in server is handed real files only (is_file, not file_exists), so a folder
//   such as /storage/ or /build/ reaches Laravel and gets the site's 404 page and its
//   security headers instead of the built-in server's bare "Not Found" page;
// - other static files are sent from here too, so they can carry cache headers (below).

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

    // The rest keep their names when they change (the 572 KB hero model, the 750 KB
    // Draco decoder, product photos), so they get an hour and then a cheap check:
    // Last-Modified goes out and a matching If-Modified-Since gets a 304 instead of the
    // whole file. PHP files are still left to the built-in server.
    if (! str_ends_with(strtolower($uri), '.php') && ! str_contains($uri, '..')) {
        $modified = filemtime($publicPath.$uri);
        $since = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '');

        if ($since !== false && $since >= $modified) {
            http_response_code(304);
            header('Cache-Control: public, max-age=3600');

            return true;
        }

        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $modified).' GMT');
        northlightSendFile($publicPath.$uri, 'public, max-age=3600');

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
