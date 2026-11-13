<?php

namespace App\Http\Controllers\Seo;

use App\Cesargb;
use App\Http\Controllers\Controller;

class RobotsTxtController extends Controller
{
    public function __invoke()
    {
        $environment = Cesargb::isProduction() ? 'production' : 'default';

        return response()->view('robots.'.$environment, [], 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
