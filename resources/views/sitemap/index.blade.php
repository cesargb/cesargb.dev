@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xhtml="http://www.w3.org/1999/xhtml"
>
    <url>
        <loc>{{ url("/es") }}</loc>
        <xhtml:link rel="alternate" hreflang="es" href="{{ url('/es') }}" />
        <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en') }}" />
    </url>
    <url>
        <loc>{{ url("/en") }}</loc>
        <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en') }}" />
        <xhtml:link rel="alternate" hreflang="es" href="{{ url('/es') }}" />
    </url>
</urlset>
