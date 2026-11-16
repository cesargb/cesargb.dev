@extends('components.layout')

@section('title', __('cv.meta_title'))
@section('description', __('cv.meta_description'))

@section('head')
    <link rel="alternate" type="text/markdown" href="{{ route(app()->getLocale().'.cv.markdown') }}" />
    <style>
        #cv {
            padding: var(--space-24) 0;
        }

        #cv .cv-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: var(--space-3);
            margin-bottom: var(--space-8);
        }

        #cv .cv-body {
            max-width: var(--measure);
            color: var(--ink-muted);
            font-size: 16px;
            line-height: 26px;
        }

        #cv .cv-photo {
            float: right;
            width: 120px;
            height: 120px;
            margin: 0 0 var(--space-4) var(--space-6);
            border: 1px solid var(--border);
            border-radius: var(--radius-full);
            object-fit: cover;
        }

        #cv .cv-body h1 {
            color: var(--ink);
            font-size: 48px;
            line-height: 54px;
            font-weight: 700;
            letter-spacing: -0.03em;
        }

        #cv .cv-body h1 + p {
            margin-top: var(--space-2);
            color: var(--accent);
            font-size: 18px;
        }

        #cv .cv-body h2 {
            margin-top: var(--space-12);
            padding-bottom: var(--space-2);
            border-bottom: 1px solid var(--border);
            color: var(--ink);
            font-family: var(--font-mono);
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        #cv .cv-body h3 {
            margin-top: var(--space-6);
            color: var(--ink);
            font-size: 18px;
            line-height: 26px;
            font-weight: 600;
        }

        #cv .cv-body h3 + p em {
            font-family: var(--font-mono);
            font-size: 13px;
            font-style: normal;
            color: var(--ink-subtle);
        }

        #cv .cv-body p {
            margin-top: var(--space-3);
        }

        #cv .cv-body ul {
            margin-top: var(--space-3);
            padding-left: var(--space-5);
            list-style: disc;
        }

        #cv .cv-body li + li {
            margin-top: var(--space-1);
        }

        #cv .cv-body li::marker {
            color: var(--accent);
        }

        #cv .cv-body strong {
            color: var(--ink);
            font-weight: 600;
        }

        #cv .cv-body a {
            color: var(--ink);
            text-decoration: underline;
            text-decoration-color: var(--accent);
            text-underline-offset: 3px;
        }

        #cv .cv-body a:hover {
            color: var(--accent);
        }

        @media (max-width: 640px) {
            #cv {
                padding: var(--space-12) 0;
            }

            #cv .cv-photo {
                width: 88px;
                height: 88px;
            }

            #cv .cv-body h1 {
                font-size: 34px;
                line-height: 40px;
            }
        }

        @media print {
            #cv .cv-actions {
                display: none;
            }
        }
    </style>
@endsection

@section('main')
    <section id="cv" class="bp-grid">
        <div class="bp-container">
            <div class="cv-actions">
                <a class="bp-btn bp-btn--primary" href="{{ route(app()->getLocale().'.cv.pdf') }}" download="cesar-garcia-cv-{{ app()->getLocale() }}.pdf">
                    {{ __('cv.download_pdf') }}
                </a>
                <a class="bp-btn bp-btn--secondary" href="{{ route(app()->getLocale().'.cv.markdown') }}" download="cesar-garcia-cv-{{ app()->getLocale() }}.md">
                    {{ __('cv.download_markdown') }}
                </a>
            </div>
            <article class="cv-body">
                <img
                    class="cv-photo"
                    src="/assets/images/profile.webp?w=240&h=240&fit=crop"
                    width="120"
                    height="120"
                    alt="César García"
                />
                {!! $html !!}
            </article>
        </div>
    </section>
@endsection
