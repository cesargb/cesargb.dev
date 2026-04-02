<?php

namespace App;

use Illuminate\Support\Facades\App;

class Cesargb
{
    public static function isProduction(): bool
    {
        if (! App::environment('production')) {
            return false;
        }

        if (App::runningInConsole()) {
            return true;
        }

        return request()->getHost() === 'www.cesargb.dev' && App::bound('request');
    }
}
