<?php

namespace App;

use Illuminate\Support\Facades\App;

class Cesargb
{
    public const PERSON_ID = 'https://www.cesargb.dev/#person';

    public const PROFILE_IMAGE = 'https://www.cesargb.dev/assets/images/profile.webp?w=800&h=800&fit=crop&fm=jpg';

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
