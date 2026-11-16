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

        #open-source-doc .doc-content pre {
            color: var(--ink);
            font: 400 14px/1.6 var(--font-mono);
        }

        #open-source-doc .doc-content pre code {
            padding: 0;
            background: transparent;
        }

        /* Syntax highlighting (tempest/highlight, rendered server-side) */
        #open-source-doc .doc-content .hl-keyword,
        #open-source-doc .doc-content .hl-operator {
            color: var(--accent);
        }

        #open-source-doc .doc-content .hl-type,
        #open-source-doc .doc-content .hl-property,
        #open-source-doc .doc-content .hl-attribute {
            color: var(--accent-2);
        }

        #open-source-doc .doc-content .hl-value,
        #open-source-doc .doc-content .hl-literal,
        #open-source-doc .doc-content .hl-number {
            color: #8fd3a8;
        }

        #open-source-doc .doc-content .hl-variable,
        #open-source-doc .doc-content .hl-generic {
            color: #9ecbff;
        }

        #open-source-doc .doc-content .hl-comment {
            color: var(--ink-subtle);
            font-style: italic;
        }

        #open-source-doc .doc-content .hl-addition {
            color: #8fd3a8;
        }

        #open-source-doc .doc-content .hl-deletion {
            color: var(--accent);
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

        #open-source-doc .code-block {
            position: relative;
        }

        #open-source-doc .code-block pre {
            padding-right: calc(var(--space-4) + 96px);
        }

        #open-source-doc .code-copy {
            position: absolute;
            top: var(--space-2);
            right: var(--space-2);
            display: inline-flex;
            align-items: center;
            gap: var(--space-1);
            min-height: 32px;
            padding: 0 var(--space-2);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--surface-raised);
            color: var(--ink-muted);
            font: 500 12px/1 var(--font-mono);
            cursor: pointer;
            opacity: 0;
            transition:
                opacity var(--dur-fast) var(--ease-out),
                color var(--dur-fast) var(--ease-out),
                border-color var(--dur-fast) var(--ease-out);
        }

        #open-source-doc .code-block:hover .code-copy,
        #open-source-doc .code-block:focus-within .code-copy,
        #open-source-doc .code-copy[data-state] {
            opacity: 1;
        }

        #open-source-doc .code-copy:hover {
            color: var(--ink);
            border-color: var(--border-strong);
        }

        #open-source-doc .code-copy:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        #open-source-doc .code-copy[data-state="copied"] {
            color: #8fd3a8;
        }

        #open-source-doc .code-copy[data-state="failed"] {
            color: var(--accent);
        }

        #open-source-doc .code-copy svg {
            width: 14px;
            height: 14px;
            flex: none;
        }

        @media (hover: none) {
            #open-source-doc .code-copy {
                opacity: 1;
            }
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!navigator.clipboard) {
                return;
            }

            const strings = {
                copy: @json(__('open-source.copy_code')),
                copied: @json(__('open-source.copied')),
                failed: @json(__('open-source.copy_failed')),
            };

            const icons = {
                copy: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>',
                copied: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>',
            };

            const live = document.createElement('span');
            live.className = 'sr-only';
            live.setAttribute('aria-live', 'polite');
            document.body.append(live);

            const render = (button, state) => {
                button.innerHTML = (state === 'copied' ? icons.copied : icons.copy) + '<span>' + strings[state] + '</span>';
                if (state === 'copy') {
                    button.removeAttribute('data-state');
                } else {
                    button.dataset.state = state;
                }
            };

            document.querySelectorAll('#open-source-doc .doc-content pre').forEach((pre) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'code-block';
                pre.replaceWith(wrapper);
                wrapper.append(pre);

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'code-copy';
                render(button, 'copy');
                wrapper.append(button);

                let timer;

                button.addEventListener('click', async () => {
                    let state = 'copied';

                    try {
                        await navigator.clipboard.writeText(pre.innerText);
                    } catch {
                        state = 'failed';
                    }

                    render(button, state);
                    live.textContent = strings[state];

                    clearTimeout(timer);
                    timer = setTimeout(() => render(button, 'copy'), 2000);
                });
            });
        });
    </script>
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
