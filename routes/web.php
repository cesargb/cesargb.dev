<?php

use App\Http\Controllers\Seo\RobotsTxtController;
use App\Http\Controllers\Seo\SitemapController;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\MetaRobotsMiddleware;
use Illuminate\Support\Facades\Route;
use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\ServerFactory;

Route::withoutMiddleware('web')->group(function () {
    Route::get('/', function () {
        return view('index');
    })->middleware([
        LanguageMiddleware::class,
        MetaRobotsMiddleware::class,
        'cache.headers:public;max_age=5;etag',
    ])->name('index');

    Route::get('/robots.txt', RobotsTxtController::class)->name('robots.txt');
    Route::get('/sitemap.xml', SitemapController::class)->name('sitemap.xml');

    Route::get('/assets/images/{path}', function ($path) {
        $server = ServerFactory::create([
            'source' => base_path('resources/images'),
            'cache' => storage_path('app/private/glide-cache'),
        ]);

        $request = request()->only('w', 'h', 'fit');

        try {
            $serverFilename = $server->makeImage($path, $request);

            return response()->file(
                storage_path("app/private/glide-cache/{$serverFilename}"),
                [
                    'Content-Type' => $server->getCache()->mimeType($serverFilename),
                    'Content-Length' => $server->getCache()->fileSize($serverFilename),
                    'Cache-Control' => 'public, max-age=31536000',
                ]
            );
        } catch (FileNotFoundException $e) {
            abort(404);
        }
    })->where('path', '.*');

    Route::prefix('/en')->name('en.')->middleware(LanguageMiddleware::class.':en')->group(function () {
        Route::middleware('cache.headers:public;max_age=5;etag')->group(function () {
            include __DIR__.'/all_lang.php';
        });
    });

    Route::prefix('/es')->name('es.')->middleware(LanguageMiddleware::class.':es')->group(function () {
        Route::middleware('cache.headers:public;max_age=5;etag')->group(function () {
            include __DIR__.'/all_lang.php';
        });
    });
});
