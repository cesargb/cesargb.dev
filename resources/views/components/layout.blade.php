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
        <meta property="og:site_name" content="César García">
        <meta property="og:locale" content="{{ $locale === 'es' ? 'es_ES' : 'en_US' }}">
        <meta property="og:locale:alternate" content="{{ $locale === 'es' ? 'en_US' : 'es_ES' }}">
        <meta property="og:image" content="{{ App\Cesargb::PROFILE_IMAGE }}">
        <meta property="og:image:type" content="image/jpeg">
        <meta property="og:image:width" content="800">
        <meta property="og:image:height" content="800">
        <meta property="og:image:alt" content="César García">
        <meta name="twitter:card" content="summary">
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
                        <li><a href="/{{ $locale }}#trajectory">{{ __('page-index.nav_career') }}</a></li>
                        <li><a href="{{ route($locale.'.experience') }}">{{ __('page-index.nav_experience') }}</a></li>
                        <li><a href="{{ route($locale.'.open-source.index') }}">{{ __('page-index.nav_projects') }}</a></li>
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
            <div class="bp-footer__top">
                <div class="bp-container bp-footer__grid">
                    <div class="bp-footer__brand">
                        <p class="bp-footer__name"><span class="bp-footer__dash" aria-hidden="true"></span>César García</p>
                        <p class="bp-footer__tagline">{{ __('page-index.footer_tagline') }}</p>
                    </div>
                    <nav class="bp-footer__col" aria-label="{{ __('page-index.footer_nav_label') }}">
                        <p class="bp-label">{{ __('page-index.footer_explore') }}</p>
                        <ul class="bp-footer__links bp-footer__links--grid">
                            <li><a href="/{{ $locale }}" @if (request()->routeIs($locale.'.index')) aria-current="page" @endif>{{ __('page-index.nav_home') }}</a></li>
                            <li><a href="/{{ $locale }}#stack">{{ __('page-index.nav_stack') }}</a></li>
                            <li><a href="/{{ $locale }}#trajectory">{{ __('page-index.nav_career') }}</a></li>
                            <li><a href="{{ route($locale.'.experience') }}" @if (request()->routeIs($locale.'.experience')) aria-current="page" @endif>{{ __('page-index.nav_experience') }}</a></li>
                            <li><a href="{{ route($locale.'.open-source.index') }}" @if (request()->routeIs($locale.'.open-source.*')) aria-current="page" @endif>{{ __('page-index.nav_projects') }}</a></li>
                            <li><a href="/{{ $locale }}#lab">{{ __('page-index.nav_lab') }}</a></li>
                        </ul>
                    </nav>
                    <div class="bp-footer__col bp-footer__col--connect">
                        <p class="bp-label">{{ __('page-index.footer_connect') }}</p>
                        <ul class="bp-footer__links">
                            <li>
                                <a href="https://github.com/cesargb" target="_blank" rel="noopener">
                                    {!! file_get_contents(resource_path('icons/github.svg')) !!}
                                    GitHub
                                    <span aria-hidden="true">↗</span>
                                    <span class="sr-only">{{ __('page-index.footer_new_tab') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="bp-footer__bottom">
                <div class="bp-container bp-footer__bar">
                    <span>© {{ date('Y') }} César García</span>
                    <x-lang-selector />
                </div>
            </div>
        </footer>
    </body>
</html>
