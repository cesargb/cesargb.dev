<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;

class RobotsTxtController extends Controller
{
    public function __invoke()
    {
        $content = "User-agent: *\nDisallow: /";

        return response($content)->header('Content-Type', 'text/plain');
    }
}
