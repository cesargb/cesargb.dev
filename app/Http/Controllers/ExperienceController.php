<?php

namespace App\Http\Controllers;

use App\Data\Skills;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    public function __invoke(): View
    {
        return view('experience', [
            'categories' => Skills::forLocale(app()->getLocale()),
        ]);
    }
}
