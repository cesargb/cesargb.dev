<?php

use Illuminate\Support\Facades\Route;

Route::prefix('lab')->name('lab.')->group(function () {
    Route::get('/', function () {
        return redirect('en/lab/js-api-translate', headers: [
            'X-Robots-Tag' => 'noindex',
        ])->setContent('');
    })->name('index');

    Route::view('/js-api-translate', 'labs.translate')->name('js-api-translate');
});
