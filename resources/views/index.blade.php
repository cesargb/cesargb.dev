<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>
            {{ __("page-index.title") }}
        </title>
        @if (Context::getHidden('meta.robots'))
        <meta name="robots" content="{{ Context::getHidden('meta.robots') }}" />
        @endif
        <meta name="description" content="{{ __("page-index.description") }}" />
        <link rel="canonical" href="{{ Context::getHidden('meta.canonical') }}" />
        <link rel="icon" href="favicon.ico" />
        @foreach (Context::getHidden('meta.hreflang') as $lang => $url)
        <link rel="alternate" hreflang="{{ $lang }}" href="{{ $url }}" />
        @endforeach
        <link rel="alternate" hreflang="x-default" href="{{ Context::getHidden('meta.hreflang-default') }}" />
        <meta property="og:url" content={{ Context::getHidden('meta.canonical') }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ __("page-index.title") }}">
        <meta property="og:description" content="{{ __("page-index.description") }}">
        <meta property="og:image" content="https://www.cesargb.dev/assets/images/profile.webp?w=800&h=800&fit=crop">
        <x-schema-person />
        @if (app()->environment('production'))
        <script defer src="https://cloud.umami.is/script.js" data-website-id="498a72ff-8feb-4ca3-8464-b60b85c73850"></script>
        @endif
        @vite('resources/css/home.css')
    </head>
    <body>
        <header>
            <nav>
                <ul class="logo">
                    <li>
                        CÉSAR<span>GARCÍA</span>
                    </li>
                </ul>
                <ul>
                     <li>
                        <a
                            href="https://github.com/cesargb"
                            target="_blank"
                            title="{{ __('page-index.header_github_alt') }}"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                fill="currentColor"
                                class="bi bi-github"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"
                                />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <x-lang-selector />
                    </li>

                </ul>
            </nav>
        </header>

        <main>
            <div class="page">
                <div class="glow"></div>

                <section class="profile">
                    <header>
                        <div class="profile__image">
                            <div class="profile__image__bg"></div>
                            <img
                                src="/assets/images/profile.webp?w=150&h=150&fit=crop"
                                alt="César García"
                            />
                        </div>

                        <div class="profile__title">
                            <h1>
                                {{ __("page-index.profile_title_hello") }}<br />
                                <strong>{{ __("page-index.profile_title_name") }}</strong>
                            </h1>
                            <div class="profile__subtitle">
                                {{ __("page-index.profile_title_profession") }}
                            </div>
                        </div>
                    </header>

                    <div class="profile__content">
                        {{ __("page-index.profile_content") }}
                    </div>

                    <div class="profile__work">
                        <div>
                            {{ __('page-index.profile_work_current_prefix')}}
                        <a href="https://www.descom.es" target="_blank">Descom.es</a>
                        </div>
                    </div>
                </section>

                <section class="experience full">
                    <div class="experience__title">
                        <div class="icon-h">
                            {!! file_get_contents(resource_path('icons/technology.svg')) !!}
                        </div>
                        <h2>
                            {{ __("page-index.experience_title") }}:
                        </h2>
                    </div>

                    <div class="experience__content">
                        <p>
                            {{ __("page-index.experience_content") }}
                        </p>
                        <div class="items">
                            @foreach(\App\Data\Experiences::all() as $experience)
                                <x-experience-item :experience="$experience" />
                            @endforeach
                        </div>

                    </div>
                </section>

                <section class="open-source">
                    <div class="open-source__title">
                        <div class="icon-h orange">
                            {!! file_get_contents(resource_path('icons/develop.svg')) !!}
                        </div>
                        <h2>
                            {{ __("page-index.projects_title") }}:
                        </h2>
                    </div>

                    <div class="open-source__content">
                        <div class="items">
                        @foreach(\App\Data\Repositories::all() as $repository)
                        <x-repository-item :repository="$repository" />
                        @endforeach
                        </div>
                    </div>
                </section>

                <section class="lab">
                    <div class="lab__title">
                        <div class="lab__title-icon">
                            <span></span>
                            <span></span>
                        </div>
                        <h2>
                            {{ __("main.lab_title") }}:
                        </h2>
                    </div>

                    <div class="lab__description">
                        {{ __("main.lab_description") }}
                    </div>

                    <a class="lab__card" href="{{ route(app()->getLocale().'.lab.js-api-translate') }}">
                        <div class="lab__card-header">
                        <div class="lab__eyebrow">
                            <span class="lab__eyebrow-icon" aria-hidden="true"></span>
                            {{ __("main.lab_experiment_label") }}
                        </div>
                            <div class="lab__card-icon">
                                {!! file_get_contents(resource_path('icons/external.svg')) !!}
                            </div>
                        </div>

                        <h3>
                            {{ __("main.lab_experiment_title") }}
                        </h3>

                        <p>
                            {{ __("main.lab_experiment_description") }}
                        </p>
                    </a>
                </section>
            </div>
        </main>
    </body>
</html>
