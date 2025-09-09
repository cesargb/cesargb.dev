<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    private static array $languages = ['en', 'es'];
    private static string $defaultLanguage = 'en';

    public function handle(Request $request, Closure $next, $languageByPath = null): Response
    {
        $currentLanguage = $languageByPath
            ?? $this->languagePreferred()
            ?? self::$defaultLanguage;

        app()->setLocale($currentLanguage);

        $canonical = $languageByPath
            ? url()->current()
            : (Route::currentRouteName() === 'index' ? route($currentLanguage . '.index') : null);

        if (! $canonical) {
            return $next($request);
        }

        Context::addHidden('meta.canonical', $canonical);

        $hrefLangs = $this->getHrefLangs($canonical);
        Context::addHidden('meta.hreflang', $hrefLangs);

        return $next($request);
    }

    private function getHrefLangs(string $canonical): array
    {
        $currentLanguage = app()->getLocale();

        $routeNameCanonical = $this->getRouteName($canonical);

        if (! $routeNameCanonical) {
            return [];
        }

        $firstSegment = Str::of($routeNameCanonical)->explode('.')->first();

        if ($currentLanguage !== $firstSegment) {
            return [];
        }

        $routeNameWithoutLang = Str::of($routeNameCanonical)->after('.')->toString();

        $hrefLangs = collect(self::$languages)
            ->mapWithKeys(fn($language) => [
                $language => $this->getUrlFromRouteName($language . '.' . $routeNameWithoutLang)
            ])
            ->filter();

        return $hrefLangs->toArray();
    }

    private function getRouteName(string $path): ?string
    {
        $request = Request::create($path, 'GET'); // crea un request falso
        $route = app('router')->getRoutes()->match($request);
        return $route->getName() ?? null;
    }

    private function getUrlFromRouteName(string $routeName): ?string
    {
        try {
            return route($routeName);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function languagePreferred(): ?string
    {
        return collect(request()->getLanguages())
            ->map(fn($locale) => explode('-', $locale)[0])
            ->first(fn($locale) => $this->isLanguagePermitted($locale));
    }

    private function isLanguagePermitted(string $language): bool
    {
        return in_array($language, self::$languages);
    }
}
