<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    public function handle(Request $request, Closure $next, $language): Response
    {
        app()->setLocale($language);

        return $next($request);
    }
}
