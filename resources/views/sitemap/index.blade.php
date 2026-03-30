@php
    echo '<?xml version="1.0" encoding="UTF-8"?>';

    $locales = ['es', 'en'];
    $paths = [
        'index',
        'lab.js-api-translate',
    ];
@endphp
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xhtml="http://www.w3.org/1999/xhtml"
>
@foreach ($paths as $path)
@foreach ($locales as $locale)
@php $routeName = "{$locale}.{$path}"; @endphp
    <url>
        <loc>{{ route($routeName) }}</loc>
@foreach ($locales as $altLocale)
        <xhtml:link rel="alternate" hreflang="{{ $altLocale }}" href="{{ route("{$altLocale}.{$path}") }}"/>
@endforeach
    </url>
@endforeach
@endforeach
</urlset>
