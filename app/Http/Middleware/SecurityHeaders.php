<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Add the response headers that tell browsers not to guess file types,
     * not to let other sites frame the shop (checkout and account pages
     * included) and not to send full page addresses to other sites. Pages
     * served over https also tell browsers to keep using https for six months.
     *
     * The Content-Security-Policy sets only the directives that need no
     * allow-list of script or image sources, so it cannot break the pages:
     * nothing may frame the shop except itself (frame-ancestors, the modern
     * form of X-Frame-Options, which stays for older browsers), a form can
     * only submit to this site, an injected <base> tag cannot redirect
     * relative links, and plugins (object/embed) are off. The shop has no
     * external form target, <base>, <object> or <iframe>.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('Content-Security-Policy', "base-uri 'self'; form-action 'self'; frame-ancestors 'self'; object-src 'none'");

        // isSecure() follows X-Forwarded-Proto through the trusted proxies, so this
        // is sent on Render and not on a plain-http local run, where browsers
        // would ignore it anyway.
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=15552000; includeSubDomains');
        }

        return $response;
    }
}
