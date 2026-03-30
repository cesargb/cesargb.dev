@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xhtml="http://www.w3.org/1999/xhtml"
>
    <url>
        <loc>{{ route('es.index') }}</loc>
        <xhtml:link rel="alternate" hreflang="es" href="{{ route('es.index') }}" />
        <xhtml:link rel="alternate" hreflang="en" href="{{ route('en.index') }}" />
    </url>
    <url>
        <loc>{{ route('en.index') }}</loc>
        <xhtml:link rel="alternate" hreflang="en" href="{{ route('en.index') }}" />
        <xhtml:link rel="alternate" hreflang="es" href="{{ route('es.index') }}" />
    </url>
    <url>
        <loc>{{ route('es.lab.js-api-translate') }}</loc>
        <xhtml:link rel="alternate" hreflang="es" href="{{ route('es.lab.js-api-translate') }}" />
        <xhtml:link rel="alternate" hreflang="en" href="{{ route('en.lab.js-api-translate') }}" />
    </url>
    <url>
        <loc>{{ route('en.lab.js-api-translate') }}</loc>
        <xhtml:link rel="alternate" hreflang="en" href="{{ route('en.lab.js-api-translate') }}" />
        <xhtml:link rel="alternate" hreflang="es" href="{{ route('es.lab.js-api-translate') }}" />
    </url>
</urlset>
