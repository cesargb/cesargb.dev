<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;

class MetaRobotsMiddleware
{
    public function handle(Request $request, Closure $next, string $defaultRobots = 'noindex, nofollow'): Response
    {
        Context::addHidden('meta.robots', $defaultRobots);

        return $next($request);
    }
}
