<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LowercaseEmail
{
    /**
     * Email addresses are compared exactly by the database, so "Sam@x.com"
     * and "sam@x.com" were two different accounts, and a phone keyboard that
     * capitalises the first letter could not sign in to an account that was
     * created in lower case. Lower-casing the address on the way in, before
     * validation, makes the unique check and the login lookup agree.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_string($request->input('email'))) {
            $request->merge(['email' => Str::lower($request->input('email'))]);
        }

        return $next($request);
    }
}
