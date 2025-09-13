<?php

use App\Http\Controllers\Seo\RobotsTxtController;
use App\Http\Controllers\Seo\SitemapController;
use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsTxtController::class)->name('robots.txt');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap.xml');

Route::get('/', function () {
    return view('index');
})->middleware(LanguageMiddleware::class)->name('index');

Route::prefix('/en')->name('en.')->middleware(LanguageMiddleware::class . ':en')->group(function () {
    Route::view('/', 'index')->name('index');
});

Route::prefix('/es')->name('es.')->middleware(LanguageMiddleware::class . ':es')->group(function () {
    Route::view('/', 'index')->name('index');
});

Route::get('/test', function () {
    return response()->json([
        'headers' => request()->headers->all(),
        'ip' => request()->ip(),
        'host' => request()->host(),
    ]);
})->name('test');
