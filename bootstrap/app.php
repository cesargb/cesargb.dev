<?php

use App\Http\Middleware\AppMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(AppMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Routes run withoutMiddleware('web'), so LanguageMiddleware never runs for an
        // unmatched route. Detect the locale from the URL before rendering the 404.
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            app()->setLocale($request->segment(1) === 'es' ? 'es' : 'en');

            return response()->view('errors.404', ['exception' => $e], 404);
        });
    })->create();
