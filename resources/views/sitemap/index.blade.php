@php
    echo '<?xml version="1.0" encoding="UTF-8"?>';

    $locales = ['es', 'en'];
    $paths = [
        ['index', []],
        ['experience', []],
        ['cv', []],
        ['lab.js-api-translate', []],
        ['open-source.index', []],
        ...array_map(
            fn ($repository) => ['open-source.show', ['repository' => $repository]],
            App\Data\Repositories::slugs()
        ),
    ];
@endphp
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xhtml="http://www.w3.org/1999/xhtml"
>
@foreach ($paths as [$path, $params])
@foreach ($locales as $locale)
@php $routeName = "{$locale}.{$path}"; @endphp
    <url>
        <loc>{{ route($routeName, $params) }}</loc>
@foreach ($locales as $altLocale)
        <xhtml:link rel="alternate" hreflang="{{ $altLocale }}" href="{{ route("{$altLocale}.{$path}", $params) }}"/>
@endforeach
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ App\Http\Middleware\LanguageMiddleware::hrefLangDefault($routeName, route("en.{$path}", $params)) }}"/>
    </url>
@endforeach
@endforeach
</urlset>
