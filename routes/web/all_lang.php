<?php

use App\Data\Repositories;
use App\Http\Controllers\CvController;
use App\Http\Controllers\OpenSourceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('index');

Route::prefix('open-source')->name('open-source.')->group(function () {
    Route::get('/', [OpenSourceController::class, 'index'])->name('index');
    Route::get('/{repository}', [OpenSourceController::class, 'show'])
        ->whereIn('repository', Repositories::slugs())
        ->name('show');
});

Route::get('/cv', [CvController::class, 'show'])->name('cv');
Route::get('/cv.md', [CvController::class, 'markdown'])->name('cv.markdown');
Route::get('/cv.pdf', [CvController::class, 'pdf'])->name('cv.pdf');
