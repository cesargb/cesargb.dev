<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;

class SitemapController extends Controller
{
    public function __invoke()
    {
        return response()->view('sitemap.index', [], 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
