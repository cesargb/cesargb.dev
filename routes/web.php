<?php

use App\Http\Controllers\RedirectLanguageController;
use App\Http\Controllers\Seo\RobotsTxtController;
use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', RedirectLanguageController::class)->name('index');

Route::get('/robots.txt', RobotsTxtController::class)->name('robots.txt');

Route::prefix('/en')->name('en.')->middleware(LanguageMiddleware::class . ':en')->group(function () {
    Route::view('/', 'index')->name('index');
});

Route::prefix('/es')->name('es.')->middleware(LanguageMiddleware::class . ':es')->group(function () {
    Route::view('/', 'index')->name('index');
});
