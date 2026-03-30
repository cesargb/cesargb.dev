<?php

use Illuminate\Support\Facades\Route;

Route::prefix('laboratorio')->name('lab.')->group(function () {
    Route::view('/', 'labs.translate')->name('js-api-translate');
});
