@extends('components.layout')

@php($locale = app()->getLocale())

@section('title', __('open-source.meta_title_project', ['project' => $title]))
@section('description', $repository['summary'][$locale])

@section('head')
    <style>
        #open-source-doc {
            padding: var(--space-24) 0;
        }

        #open-source-doc .doc-container {
            width: 100%;
            max-width: calc(var(--measure) + 2 * var(--space-6));
            margin: 0 auto;
            padding: 0 var(--space-6);
        }

        #open-source-doc .doc-back {
            display: inline-block;
            margin-bottom: var(--space-8);
            font: 500 12px/16px var(--font-mono);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink-subtle);
        }

        #open-source-doc .doc-back:hover {
            color: var(--accent);
        }

        #open-source-doc .doc-header {
            display: grid;
            gap: var(--space-4);
            justify-items: start;
            padding-bottom: var(--space-8);
            margin-bottom: var(--space-8);
            border-bottom: 1px solid var(--border);
        }

        #open-source-doc .doc-repo {
            font: 500 14px/20px var(--font-mono);
            color: var(--ink-subtle);
        }

        #open-source-doc .doc-title {
            font-size: 44px;
            line-height: 50px;
            font-weight: 700;
            letter-spacing: -0.03em;
        }

        #open-source-doc .doc-summary {
            color: var(--ink-muted);
            font-size: 18px;
            line-height: 28px;
        }

        #open-source-doc .doc-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: var(--space-3);
        }

        #open-source-doc .doc-content {
            color: var(--ink-muted);
            font-size: 16px;
            line-height: 28px;
        }

        #open-source-doc .doc-content > * + * {
            margin-top: var(--space-4);
        }

        #open-source-doc .doc-content h2 {
            margin-top: var(--space-12);
            font-size: 26px;
            line-height: 34px;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--ink);
        }

        #open-source-doc .doc-content h3 {
            margin-top: var(--space-8);
            font-size: 19px;
            line-height: 28px;
            font-weight: 600;
            color: var(--ink);
        }

        #open-source-doc .doc-content a {
            color: var(--accent);
        }

        #open-source-doc .doc-content ul,
        #open-source-doc .doc-content ol {
            padding-left: var(--space-6);
        }

        #open-source-doc .doc-content code {
            padding: 1px var(--space-1);
            border-radius: var(--radius-sm);
            background: var(--surface-raised);
            color: var(--ink);
            font: 400 14px/1.5 var(--font-mono);
        }

        #open-source-doc .doc-content pre {
            overflow-x: auto;
            padding: var(--space-4);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: var(--surface);
        }

        #open-source-doc .doc-content pre code {
            padding: 0;
            background: transparent;
        }

        #open-source-doc .doc-content table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        #open-source-doc .doc-content th,
        #open-source-doc .doc-content td {
            padding: var(--space-2) var(--space-3);
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        @media (max-width: 640px) {
            #open-source-doc {
                padding: var(--space-12) 0;
            }

            #open-source-doc .doc-container {
                padding: 0 var(--space-4);
            }

            #open-source-doc .doc-title {
                font-size: 32px;
                line-height: 38px;
            }
        }
    </style>
@endsection

@section('main')
    <section id="open-source-doc">
        <div class="doc-container">
            <a class="doc-back" href="{{ route($locale.'.open-source.index') }}">← {{ __('open-source.back_to_index') }}</a>

            <header class="doc-header">
                <span class="doc-repo">{{ $repository['owner'] }}/{{ $repository['repository'] }}</span>
                <h1 class="doc-title">{{ $title }}</h1>
                <p class="doc-summary">{{ $repository['summary'][$locale] }}</p>
                <div class="doc-meta">
                    @foreach ($repository['tags'] as $tag)
                        <span class="bp-tag">{{ $tag }}</span>
                    @endforeach
                    <a
                        class="bp-btn bp-btn--secondary"
                        href="https://github.com/{{ $repository['owner'] }}/{{ $repository['repository'] }}"
                        target="_blank"
                        rel="noopener"
                    >
                        {!! file_get_contents(resource_path('icons/github.svg')) !!}
                        {{ __('open-source.view_on_github') }}
                    </a>
                </div>
            </header>

            <article class="doc-content">
                {!! $content !!}
            </article>
        </div>
    </section>
@endsection
