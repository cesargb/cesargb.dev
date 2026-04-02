<?php

namespace App\Http\Middleware;

use App\Cesargb;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;

class AppMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Cesargb::isProduction()) {
            Context::addHidden('meta.robots', 'noindex,nofollow');
        }

        return $next($request);
    }
}
