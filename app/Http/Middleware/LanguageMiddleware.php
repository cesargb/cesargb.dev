<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;
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

        if (Route::currentRouteName() === 'index') {
            return redirect()
                ->route(route: $currentLanguage.'.index', status: 302, headers: [
                    'X-Robots-Tag' => 'noindex',
                    'Vary' => 'Accept-Language',
                ])
                ->setContent('');
        }

        $canonical = $languageByPath
            ? url()->current()
            : (Route::currentRouteName() === 'index' ? route($currentLanguage.'.index') : null);

        if (! $canonical) {
            return $next($request);
        }

        $hrefLangs = $this->getHrefLangs($canonical);

        Context::addHidden('meta.canonical', Uri::of($canonical)->value());
        Context::addHidden('meta.hreflang', array_map(
            fn ($url) => Uri::of($url)->value(),
            $hrefLangs
        ));
        Context::addHidden('meta.hreflang-default', Uri::of(
            self::hrefLangDefault(Route::currentRouteName(), $hrefLangs['en'] ?? $canonical)
        )->value());

        return $next($request);
    }

    /**
     * The x-default alternate: the root URL for the home page (it redirects to the
     * visitor's language), the English version for any other page.
     */
    public static function hrefLangDefault(?string $routeName, string $englishUrl): string
    {
        return Str::after((string) $routeName, '.') === 'index' ? url('/') : $englishUrl;
    }

    private function getHrefLangs(string $canonical): array
    {
        $currentLanguage = app()->getLocale();

        $routeCanonical = $this->getRoute($canonical);
        $routeNameCanonical = $routeCanonical->getName();

        if (! $routeNameCanonical) {
            return [];
        }

        $firstSegment = Str::of($routeNameCanonical)->explode('.')->first();

        if ($currentLanguage !== $firstSegment) {
            return [];
        }

        $routeNameWithoutLang = Str::of($routeNameCanonical)->after('.')->toString();

        $hrefLangs = collect(self::$languages)
            ->mapWithKeys(fn ($language) => [
                $language => $this->getUrlFromRouteName(
                    $language.'.'.$routeNameWithoutLang,
                    $this->getPathParameters($routeCanonical),
                ),
            ])
            ->filter();

        return $hrefLangs->toArray();
    }

    private function getRoute(string $path): RoutingRoute
    {
        $request = Request::create($path, 'GET');

        return (clone app('router')->getRoutes()->match($request))->bind($request);
    }

    /**
     * Only the parameters declared in the URI: `Route::view()` stores its view and data as
     * route defaults, which would otherwise leak into the alternate URLs as a query string.
     */
    private function getPathParameters(RoutingRoute $route): array
    {
        return array_intersect_key($route->parameters(), array_flip($route->parameterNames()));
    }

    private function getUrlFromRouteName(string $routeName, array $parameters = []): ?string
    {
        try {
            return route($routeName, $parameters);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function languagePreferred(): ?string
    {
        return collect(request()->getLanguages())
            ->map(fn ($locale) => preg_split('/[-_]/', $locale)[0])
            ->first(fn ($locale) => $this->isLanguagePermitted($locale));
    }

    private function isLanguagePermitted(string $language): bool
    {
        return in_array($language, self::$languages);
    }
}
