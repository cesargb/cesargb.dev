<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;

class AppMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->host() !== '127.0.0.1') {
            Context::addHidden('meta.robots', 'noindex,nofollow');
        }

        return $next($request);
    }
}
