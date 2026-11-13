@extends('components.layout')

@section('main')
    <section class="bp-hero bp-grid" aria-labelledby="hero-title">
        <div class="bp-container bp-hero__inner">
            <div class="bp-hero__id">
                <div class="bp-avatar bp-corners">
                    <img
                        src="/assets/images/profile.webp?w=144&h=144&fit=crop"
                        alt="César García"
                        width="62"
                        height="62"
                    />
                </div>
                <span class="bp-label">{{ __('page-index.profile_eyebrow') }}</span>
            </div>

            <h1 id="hero-title">
                {{ __('page-index.profile_title_hello') }}
                <span>{{ __('page-index.profile_title_name') }}</span>
            </h1>

            <p class="bp-hero__lead">{{ __('page-index.profile_content') }}</p>

            <div class="bp-hero__actions">
                <a class="bp-btn bp-btn--primary" href="#open-source">
                    {{ __('page-index.profile_cta_projects') }}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                </a>
                <a class="bp-btn bp-btn--secondary" href="https://github.com/cesargb" target="_blank" rel="noopener">
                    {!! file_get_contents(resource_path('icons/github.svg')) !!}
                    {{ __('page-index.profile_cta_github') }}
                </a>
            </div>

            <p class="bp-badge">
                {{ __('page-index.profile_work_current_prefix') }}
                <a href="https://www.descom.es" target="_blank" rel="noopener">Descom.es ↗</a>
            </p>
        </div>
    </section>

    <section class="bp-section" id="stack" aria-labelledby="stack-title">
        <div class="bp-container">
            <x-section-header id="stack-title" eyebrow="01 · {{ __('page-index.experience_eyebrow') }}" :title="__('page-index.experience_title')">
                {{ __('page-index.experience_content') }}
            </x-section-header>

            <ol class="bp-stack bp-grid" role="list">
                @foreach (\App\Data\Experiences::all() as $experience)
                    <x-experience-item :experience="$experience" />
                @endforeach
            </ol>
        </div>
    </section>

    <section class="bp-section" id="open-source" aria-labelledby="open-source-title">
        <div class="bp-container">
            <x-section-header id="open-source-title" eyebrow="02 · {{ __('page-index.projects_eyebrow') }}" :title="__('page-index.projects_title')">
                {{ __('page-index.projects_content') }}
            </x-section-header>

            <div class="bp-cards">
                @foreach (\App\Data\Repositories::all() as $repository)
                    <x-repository-item :repository="$repository" :index="$loop->iteration" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="bp-section" id="lab" aria-labelledby="lab-title">
        <div class="bp-container">
            <x-section-header id="lab-title" eyebrow="03 · {{ __('page-index.lab_eyebrow') }}" :title="__('main.lab_title')">
                {{ __('main.lab_description') }}
            </x-section-header>

            <div class="bp-cards">
                <a class="bp-card" href="{{ route(app()->getLocale().'.lab.js-api-translate') }}">
                    <div class="bp-card__head">
                        <div class="bp-card__repo">
                            <span class="bp-label bp-status">{{ __('main.lab_experiment_label') }}</span>
                            <h3 class="bp-card__title">{{ __('main.lab_experiment_title') }}</h3>
                        </div>
                        <span class="bp-card__arrow" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7"/><path d="M7 7h10v10"/></svg>
                        </span>
                    </div>
                    <p>{{ __('main.lab_experiment_description') }}</p>
                    <div class="bp-card__tags">
                        <span class="bp-tag">chrome</span>
                        <span class="bp-tag">gemini-nano</span>
                        <span class="bp-tag">translator-api</span>
                    </div>
                </a>
            </div>
        </div>
    </section>
@endsection
