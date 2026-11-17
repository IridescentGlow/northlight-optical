<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveTrailingSlash
{
    /**
     * Send /about/, /products/ and the like permanently to the address without
     * the trailing slash, keeping any query string.
     *
     * Laravel's public/.htaccess does this under Apache, but the deploy runs
     * `php artisan serve`, which never reads it, so every page also answered at a
     * second address. Only GET and HEAD are redirected: a 301 turns a POST into a
     * GET.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->getPathInfo();

        if ($request->isMethodCacheable() && $path !== '/' && str_ends_with($path, '/')) {
            $query = $request->getQueryString();

            return redirect()->to(
                $request->getSchemeAndHttpHost().$request->getBaseUrl().rtrim($path, '/').($query !== null ? '?'.$query : ''),
                301
            );
        }

        return $next($request);
    }
}
