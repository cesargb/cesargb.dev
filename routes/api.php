<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return [
        'host' => request()->getHost(),
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'headers' => request()->headers->all(),
    ];
});
