@php($locale = app()->getLocale())
<!DOCTYPE html>
<html lang="{{ $locale }}">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="theme-color" content="#0a0a0b" />
        <title>@yield('title', __("page-index.title"))</title>
        @if (Context::getHidden('meta.robots'))
        <meta name="robots" content="{{ Context::getHidden('meta.robots') }}" />
        @endif
        <meta name="description" content="@yield('description', __("page-index.description"))" />
        <link rel="canonical" href="{{ Context::getHidden('meta.canonical') }}" />
        <link rel="icon" href="/favicon.ico" />
        @foreach (Context::getHidden('meta.hreflang') as $lang => $url)
        <link rel="alternate" hreflang="{{ $lang }}" href="{{ $url }}" />
        @endforeach
        <link rel="alternate" hreflang="x-default" href="{{ Context::getHidden('meta.hreflang-default') }}" />
        <meta property="og:url" content="{{ Context::getHidden('meta.canonical') }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="@yield('title', __('page-index.title'))">
        <meta property="og:description" content="@yield('description', __('page-index.description'))">
        <meta property="og:image" content="https://www.cesargb.dev/assets/images/profile.webp?w=800&h=800&fit=crop">
        <x-schema-person />
        <link rel="preload" href="{{ Vite::asset('node_modules/@fontsource-variable/inter/files/inter-latin-wght-normal.woff2') }}" as="font" type="font/woff2" crossorigin />
        <link rel="preload" href="{{ Vite::asset('node_modules/@fontsource/jetbrains-mono/files/jetbrains-mono-latin-500-normal.woff2') }}" as="font" type="font/woff2" crossorigin />
        @vite('resources/css/app.css')
        @yield('head')
        @if (App\Cesargb::isProduction())
        <link rel="preconnect" href="https://gateway.umami.is" crossorigin />
        <script defer src="https://cloud.umami.is/script.js" data-website-id="498a72ff-8feb-4ca3-8464-b60b85c73850"></script>
        @endif
    </head>
    <body>
        <a class="skip-link" href="#content">{{ __('page-index.skip_to_content') }}</a>

        <header class="bp-header">
            <div class="bp-container">
                <a class="bp-logo" href="/{{ $locale }}">
                    <span class="bp-logo__mark" aria-hidden="true">~/</span>
                    <span>césar<span class="bp-logo__accent">garcía</span></span>
                </a>

                <nav class="bp-nav" aria-label="{{ __('page-index.nav_label') }}">
                    <ul class="bp-nav__links">
                        <li><a href="/{{ $locale }}#stack">{{ __('page-index.nav_stack') }}</a></li>
                        <li><a href="/{{ $locale }}#open-source">{{ __('page-index.nav_projects') }}</a></li>
                        <li><a href="/{{ $locale }}#lab">{{ __('page-index.nav_lab') }}</a></li>
                    </ul>
                    <x-lang-selector />
                    <a
                        class="bp-icon-btn"
                        href="https://github.com/cesargb"
                        target="_blank"
                        rel="noopener"
                        aria-label="{{ __('page-index.header_github_alt') }}"
                        title="{{ __('page-index.header_github_alt') }}"
                    >
                        {!! file_get_contents(resource_path('icons/github.svg')) !!}
                    </a>
                </nav>
            </div>
        </header>

        <main id="content">
            @yield('main')
        </main>

        <footer class="bp-footer">
            <div class="bp-container">
                <span>© {{ date('Y') }} César García</span>
                <ul>
                    <li><a href="https://github.com/cesargb" target="_blank" rel="noopener">GitHub</a></li>
                    <li><a href="https://www.descom.es" target="_blank" rel="noopener">Descom.es</a></li>
                    <li><span class="bp-label">{{ __('page-index.footer_built_with') }}</span></li>
                </ul>
            </div>
        </footer>
    </body>
</html>
