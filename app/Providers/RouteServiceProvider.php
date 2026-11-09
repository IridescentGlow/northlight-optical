<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Sign-in attempts are counted per email address as well as per IP.
        // Behind the host's proxies the IP the app sees can differ from one
        // request to the next, or be set by the caller through
        // X-Forwarded-For, so on its own it let attempts at one account
        // spread across several counters.
        RateLimiter::for('login', function (Request $request) {
            $email = $request->input('email');

            return [
                Limit::perMinute(10)->by('login|'.(is_string($email) ? Str::lower($email) : '')),
                Limit::perMinute(30)->by('login-ip|'.$request->ip()),
            ];
        });

        // The contact and sign-up forms. A per-IP count alone does not hold here: behind the
        // host's proxies the address the app sees changes between requests, and a caller can
        // set it through X-Forwarded-For (a test with one fixed header value was still let
        // through on most requests). The site-wide ceiling does not depend on the address,
        // so a script cannot get round it; it is set well above what real visitors do.
        RateLimiter::for('contact', fn (Request $request) => [
            Limit::perMinute(5)->by('contact-ip|'.$request->ip()),
            Limit::perMinute(30)->by('contact-all'),
        ]);

        RateLimiter::for('register', fn (Request $request) => [
            Limit::perMinute(10)->by('register-ip|'.$request->ip()),
            Limit::perMinute(30)->by('register-all'),
        ]);

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
