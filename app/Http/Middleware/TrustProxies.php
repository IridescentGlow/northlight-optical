<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Trusts all — the app only ever receives connections from its host
     * platform's own edge/load balancer on a private network (Railway,
     * or any similar container platform), never directly from the
     * internet. Left at the framework default of null (trust nothing),
     * $request->secure() reads false behind any TLS-terminating proxy
     * even when the client's actual connection was HTTPS, which silently
     * makes url()/asset() emit http:// links — broken images, wrong
     * redirect scheme — despite APP_URL being set to https://.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
