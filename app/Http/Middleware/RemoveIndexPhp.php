<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveIndexPhp
{
    /**
     * Send /index.php and /index.php/... permanently to the same address
     * without the script name.
     *
     * Laravel treats a leading /index.php as the base address, so without this
     * /index.php served the home page with every link, form and the canonical tag
     * rewritten to /index.php/..., a full second copy of the shop for search
     * engines to find. Only GET and HEAD are redirected: a 301 turns a POST into
     * a GET, and no page links a form there once its pages redirect.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uri = $request->getRequestUri();

        if ($request->isMethodCacheable() && preg_match('#^/index\.php(?=$|[/?])#', $uri)) {
            $rest = substr($uri, strlen('/index.php'));

            return redirect()->to($request->getSchemeAndHttpHost().'/'.ltrim($rest, '/'), 301);
        }

        return $next($request);
    }
}
