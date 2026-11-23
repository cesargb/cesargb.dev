@extends('components.layout')

@section('title', __('open-source.meta_title'))
@section('description', __('open-source.meta_description'))

@section('head')
    <style>
        #open-source-index {
            padding: var(--space-24) 0;
        }

        #open-source-index .os-hero {
            display: grid;
            gap: var(--space-4);
            justify-items: start;
            margin-bottom: var(--space-12);
        }

        #open-source-index .os-title {
            font-size: 48px;
            line-height: 54px;
            font-weight: 700;
            letter-spacing: -0.03em;
            max-width: 20ch;
        }

        #open-source-index .os-title .accent {
            color: var(--accent);
        }

        #open-source-index .os-intro {
            max-width: var(--measure);
            color: var(--ink-muted);
            font-size: 18px;
            line-height: 28px;
        }

        @media (max-width: 640px) {
            #open-source-index {
                padding: var(--space-12) 0;
            }

            #open-source-index .os-title {
                font-size: 34px;
                line-height: 40px;
            }
        }
    </style>
@endsection

@section('main')
    <section id="open-source-index" class="bp-grid">
        <div class="bp-container">
            <div class="os-hero">
                <span class="bp-label bp-status">{{ __('open-source.badge') }}</span>
                <h1 class="os-title">
                    {{ __('open-source.title_prefix') }}
                    <span class="accent">{{ __('open-source.title_accent') }}</span>
                </h1>
                <p class="os-intro">{{ __('open-source.intro') }}</p>
            </div>

            <div class="bp-cards">
                @foreach ($repositories as $repository)
                    <x-repository-item
                        :repository="$repository"
                        :index="$loop->iteration"
                        :level="2"
                        :href="route(app()->getLocale().'.open-source.show', $repository['repository'])"
                    />
                @endforeach
            </div>
        </div>
    </section>
@endsection
