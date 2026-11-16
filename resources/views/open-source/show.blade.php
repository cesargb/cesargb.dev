@extends('components.layout')

@php
    $locale = app()->getLocale();
    $hasToc = count($toc) >= 5;
@endphp

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

        #open-source-doc .doc-container--with-toc {
            max-width: var(--container);
        }

        #open-source-doc .doc-main {
            min-width: 0;
            max-width: var(--measure);
        }

        #open-source-doc .doc-toc {
            margin-bottom: var(--space-8);
            padding: var(--space-4);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: var(--surface);
        }

        #open-source-doc .doc-toc__title {
            display: block;
            margin-bottom: var(--space-3);
            font: 500 12px/16px var(--font-mono);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink-subtle);
        }

        #open-source-doc .doc-toc ul {
            display: grid;
            gap: var(--space-1);
            margin: 0;
            padding: 0;
            list-style: none;
        }

        #open-source-doc .doc-toc a {
            display: block;
            padding: 2px 0;
            color: var(--ink-muted);
            font-size: 14px;
            line-height: 20px;
            text-decoration: none;
        }

        #open-source-doc .doc-toc a:hover {
            color: var(--ink);
        }

        #open-source-doc .doc-toc .doc-toc__item--3 a {
            padding-left: var(--space-4);
            font-size: 13px;
            color: var(--ink-subtle);
        }

        #open-source-doc .doc-toc a[aria-current="true"] {
            color: var(--accent);
        }

        @media (min-width: 1024px) {
            /* The index sits in a left column; .doc-main's children join the parent grid. */
            #open-source-doc .doc-container--with-toc {
                display: grid;
                grid-template-columns: 240px minmax(0, var(--measure));
                column-gap: var(--space-12);
                align-items: start;
            }

            #open-source-doc .doc-container--with-toc .doc-main {
                display: contents;
            }

            #open-source-doc .doc-container--with-toc .doc-main > * {
                grid-column: 2;
            }

            #open-source-doc .doc-container--with-toc .doc-back {
                justify-self: start;
            }

            #open-source-doc .doc-container--with-toc .doc-main > .doc-toc {
                grid-column: 1;
                grid-row: 1 / span 3;
                align-self: start;
                position: sticky;
                top: calc(var(--header-h) + var(--space-6));
                max-height: calc(100vh - var(--header-h) - var(--space-12));
                overflow-y: auto;
                margin-bottom: 0;
                padding: 0 var(--space-4) 0 0;
                border: none;
                border-right: 1px solid var(--border);
                border-radius: 0;
                background: transparent;
            }

            #open-source-doc .doc-toc a {
                border-left: 2px solid transparent;
                margin-left: -2px;
                padding-left: var(--space-3);
            }

            #open-source-doc .doc-toc .doc-toc__item--3 a {
                padding-left: var(--space-6);
            }

            #open-source-doc .doc-toc a[aria-current="true"] {
                border-left-color: var(--accent);
            }
        }

        #open-source-doc .doc-content .heading-anchor {
            margin-left: var(--space-2);
            color: var(--ink-subtle);
            text-decoration: none;
            opacity: 0;
            transition: opacity var(--dur-fast) var(--ease-out);
        }

        #open-source-doc .doc-content :is(h2, h3, h4):hover .heading-anchor {
            opacity: 1;
        }

        #open-source-doc .doc-content .heading-anchor:hover {
            color: var(--accent);
        }

        #open-source-doc .doc-content h4 {
            margin-top: var(--space-6);
            font-size: 16px;
            line-height: 24px;
            font-weight: 600;
            color: var(--ink);
        }

        #open-source-doc .doc-content blockquote {
            margin-left: 0;
            margin-right: 0;
            padding: var(--space-3) var(--space-4);
            border-left: 3px solid var(--accent);
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
            background: var(--accent-soft);
            color: var(--ink);
        }

        #open-source-doc .doc-content blockquote > * + * {
            margin-top: var(--space-2);
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const links = new Map();

            document.querySelectorAll('#open-source-doc .doc-toc a[href^="#"]').forEach((link) => {
                links.set(decodeURIComponent(link.hash.slice(1)), link);
            });

            if (links.size === 0 || !('IntersectionObserver' in window)) {
                return;
            }

            const headings = [...links.keys()]
                .map((id) => document.getElementById(id))
                .filter(Boolean);

            const activate = (id) => {
                links.forEach((link, key) => {
                    if (key === id) {
                        link.setAttribute('aria-current', 'true');
                    } else {
                        link.removeAttribute('aria-current');
                    }
                });
            };

            const observer = new IntersectionObserver(() => {
                const offset = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--header-h')) || 64;
                let current = headings[0];

                for (const heading of headings) {
                    if (heading.getBoundingClientRect().top - offset - 32 <= 0) {
                        current = heading;
                    }
                }

                activate(current.id);
            }, { rootMargin: '0px 0px -60% 0px' });

            headings.forEach((heading) => observer.observe(heading));
        });
    </script>
@endsection

@section('main')
    <section id="open-source-doc">
        <div @class(['doc-container', 'doc-container--with-toc' => $hasToc])>
            <div class="doc-main">
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

                @if ($hasToc)
                    <aside class="doc-toc">
                        <nav aria-labelledby="doc-toc-title">
                            <span class="doc-toc__title" id="doc-toc-title">{{ __('open-source.toc_title') }}</span>
                            <ul>
                                @foreach ($toc as $entry)
                                    <li class="doc-toc__item--{{ $entry['level'] }}">
                                        <a href="#{{ $entry['id'] }}">{{ $entry['text'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </aside>
                @endif

                <article class="doc-content">
                    {!! $content !!}
                </article>
            </div>
        </div>
    </section>
@endsection
