<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;

class RobotsTxtController extends Controller
{
    public function __invoke()
    {
        return response()->view('robots', [], 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
