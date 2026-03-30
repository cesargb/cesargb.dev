<?php

use Illuminate\Support\Facades\Route;

Route::prefix('laboratorio')->name('lab.')->group(function () {
    Route::get('/', function () {
        return redirect('es/laboratorio/js-api-traduccion', headers: [
            'X-Robots-Tag' => 'noindex',
        ])->setContent('');
    })->name('index');

    Route::view('/js-api-traduccion', 'labs.translate')->name('js-api-translate');
});
