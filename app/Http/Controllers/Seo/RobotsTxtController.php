<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class RobotsTxtController extends Controller
{
    public function __invoke()
    {
        $environment = App::environment('production') ? 'production' : 'default';

        return response()->view('robots.'.$environment, [], 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
