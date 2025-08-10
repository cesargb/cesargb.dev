<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectLanguageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $language = collect($request->getLanguages())->map(fn($locale) => explode('-', $locale)[0])
            ->first(fn($locale) => in_array($locale, ['en', 'es'])) ?? 'en';

        return redirect()->to("/{$language}");
    }
}
