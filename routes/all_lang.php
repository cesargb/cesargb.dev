<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('index');

Route::prefix('labs')->name('labs.')->group(function () {
    Route::view('/', 'labs.translate')->name('translate');
});
