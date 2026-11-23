@extends('components.layout')

@section('title', __('experience.meta_title'))
@section('description', __('experience.meta_description'))

@section('head')
    @vite('resources/js/experience.js')
    <style>
        .xp-space {
            position: relative;
            height: calc(100dvh - var(--header-h));
            min-height: 420px;
            overflow: hidden;
            background: radial-gradient(ellipse at center, #121218 0%, var(--bg) 70%);
            user-select: none;
            -webkit-user-select: none;
        }

        .xp-space canvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            display: block;
            /* Vertical swipes scroll the page; horizontal drags speed up the journey */
            touch-action: pan-y;
            cursor: grab;
            outline: none;
        }

        .xp-space canvas:active {
            cursor: grabbing;
        }

        .xp-space canvas:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: -2px;
        }

        .xp-labels {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .xp-star-label {
            position: absolute;
            top: 0;
            left: 0;
            padding: 2px 8px;
            border-radius: var(--radius-full);
            color: var(--ink);
            font-family: var(--font-mono);
            font-weight: 500;
            white-space: nowrap;
            text-shadow: 0 0 8px rgb(0 0 0 / 0.9);
            opacity: 0;
            will-change: transform, opacity;
        }

        .xp-star-label small {
            display: none;
            margin-left: 6px;
            color: var(--star-color);
            font-size: 0.85em;
        }

        .xp-star-label.is-active {
            background: rgb(10 10 11 / 0.75);
            box-shadow: 0 0 0 1px var(--star-color);
        }

        .xp-star-label.is-active small {
            display: inline;
        }

        .xp-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: var(--space-6);
            pointer-events: none;
            /* Keeps the text legible over the moving stars */
            text-shadow: 0 0 8px rgb(0 0 0 / 0.9);
        }

        .xp-intro {
            display: grid;
            gap: var(--space-2);
            max-width: 46ch;
            transition: opacity 1.2s var(--ease-out);
        }

        .xp-intro h1 {
            margin: 0;
            font-size: clamp(28px, 4vw, 44px);
            line-height: 1.1;
            letter-spacing: -0.03em;
        }

        .xp-intro p {
            margin: 0;
            color: var(--ink-muted);
        }

        .xp-space.is-travelling .xp-intro {
            opacity: 0.35;
        }

        .xp-hud {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: var(--space-4);
        }

        .xp-legend {
            display: flex;
            flex-wrap: wrap;
            gap: var(--space-2) var(--space-4);
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .xp-legend li {
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
            color: var(--ink-muted);
            font-size: 13px;
        }

        .xp-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--star-color);
            box-shadow: 0 0 10px var(--star-color);
            flex: none;
        }

        .xp-controls {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: var(--space-4);
        }

        .xp-toggle {
            pointer-events: auto;
            text-shadow: none;
        }

        .xp-toggle .xp-toggle__play,
        .xp-toggle[aria-pressed="true"] .xp-toggle__pause {
            display: none;
        }

        .xp-toggle[aria-pressed="true"] .xp-toggle__play {
            display: block;
        }

        .xp-meter {
            display: grid;
            gap: var(--space-1);
            justify-items: end;
            text-align: right;
        }

        .xp-meter__bar {
            width: 140px;
            height: 4px;
            border-radius: var(--radius-full);
            background: var(--border);
            overflow: hidden;
        }

        .xp-meter__bar span {
            display: block;
            height: 100%;
            width: 0;
            background: var(--accent);
        }

        .xp-hint {
            color: var(--ink-subtle);
            font-size: 12px;
        }

        .xp-hint--keys {
            display: none;
        }

        @media (hover: hover) and (pointer: fine) {
            .xp-hint--keys {
                display: block;
            }
        }

        /* Without JavaScript or WebGL the space stays empty: hide it and keep the list. */
        .xp-space:not(.is-ready) .xp-hud {
            visibility: hidden;
        }

        .xp-space canvas {
            opacity: 0;
            transition: opacity 0.9s var(--ease-out);
        }

        .xp-space.is-ready canvas {
            opacity: 1;
        }

        /* CSS-only tunnel shown while Three.js loads; it fades out on the first 3D frame. */
        .xp-preview {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.9s var(--ease-out), visibility 0s linear 0.9s;
            background: radial-gradient(circle at center, rgb(124 131 255 / 0.18) 0%, transparent 35%);
        }

        .xp-space.is-loading:not(.is-ready) .xp-preview {
            opacity: 1;
            visibility: visible;
            transition: none;
        }

        .xp-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 70vmin;
            height: 70vmin;
            margin: -35vmin 0 0 -35vmin;
            border: 1px solid rgb(154 160 255 / 0.55);
            border-radius: 50%;
            opacity: 0;
            animation: xp-ring 4s cubic-bezier(0.55, 0, 1, 0.45) infinite;
            animation-delay: calc(var(--i) * -0.5s);
        }

        @keyframes xp-ring {
            0% {
                transform: scale(0.04);
                opacity: 0;
            }

            25% {
                opacity: 0.7;
            }

            100% {
                transform: scale(2.6);
                opacity: 0;
            }
        }

        .xp-preview__label {
            position: absolute;
            left: 50%;
            top: calc(50% + 48px);
            transform: translateX(-50%);
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
            white-space: nowrap;
        }

        .xp-preview__label::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent);
            animation: xp-blink 1.2s ease-in-out infinite;
        }

        @keyframes xp-blink {
            50% {
                opacity: 0.2;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .xp-ring {
                animation: none;
                opacity: 0.35;
                transform: scale(calc(0.15 + var(--i) * 0.3));
            }

            .xp-preview__label::before {
                animation: none;
            }
        }

        /* Without JavaScript, after the load timeout or without WebGL: just the intro */
        .xp-space:not(.is-loading, .is-ready) {
            height: auto;
            min-height: 0;
            padding-bottom: var(--space-12);
        }

        .xp-space:not(.is-loading, .is-ready) .xp-overlay {
            position: static;
        }

        .xp-list__grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: var(--space-8);
        }

        .xp-list h3 {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            margin: 0 0 var(--space-3);
            font-size: 16px;
        }

        .xp-list ul {
            display: grid;
            gap: var(--space-2);
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .xp-list li {
            display: flex;
            justify-content: space-between;
            gap: var(--space-3);
            color: var(--ink-muted);
            font-size: 14px;
        }

        .xp-level {
            font-family: var(--font-mono);
            letter-spacing: 2px;
            color: var(--star-color);
        }

        .xp-level span {
            opacity: 0.25;
        }
    </style>
@endsection

@section('main')
    <section class="xp-space" aria-labelledby="xp-title" data-xp>
        <canvas
            tabindex="0"
            role="img"
            aria-label="{{ __('experience.content') }}"
            aria-describedby="xp-keys"
        ></canvas>
        <span class="sr-only" id="xp-keys">{{ __('experience.hint_keys') }}</span>
        <span class="sr-only" aria-live="polite" data-xp-status></span>
        <div class="xp-preview" aria-hidden="true">
            @for ($i = 0; $i < 8; $i++)
                <span class="xp-ring" style="--i: {{ $i }}"></span>
            @endfor
            <span class="xp-preview__label bp-label">{{ __('experience.loading') }}</span>
        </div>
        <div class="xp-labels" aria-hidden="true"></div>

        <div class="xp-overlay">
            <div class="xp-intro">
                <span class="bp-label">{{ __('experience.eyebrow') }}</span>
                <h1 id="xp-title">{{ __('experience.title') }}</h1>
                <p>{{ __('experience.content') }}</p>
            </div>

            <div class="xp-hud">
                <ul class="xp-legend" aria-label="{{ __('experience.legend') }}">
                    @foreach ($categories as $category)
                        <li style="--star-color: {{ $category['color'] }}">
                            <span class="xp-dot" aria-hidden="true"></span>{{ $category['name'] }}
                        </li>
                    @endforeach
                </ul>

                <div class="xp-controls">
                    <button
                        type="button"
                        class="bp-btn bp-btn--secondary xp-toggle"
                        aria-pressed="false"
                        data-xp-toggle
                        data-pause="{{ __('experience.pause') }}"
                        data-resume="{{ __('experience.resume') }}"
                    >
                        <svg class="xp-toggle__pause" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 5v14"/><path d="M16 5v14"/></svg>
                        <svg class="xp-toggle__play" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m7 4 13 8-13 8z"/></svg>
                        <span data-xp-toggle-label>{{ __('experience.pause') }}</span>
                    </button>

                    <div class="xp-meter" aria-hidden="true">
                        <span class="bp-label" data-xp-speed-label data-speed="{{ __('experience.speed') }}" data-paused="{{ __('experience.paused') }}">{{ __('experience.speed') }}</span>
                        <div class="xp-meter__bar"><span data-xp-speed></span></div>
                        <span class="xp-hint">{{ __('experience.hint') }}</span>
                        <span class="xp-hint xp-hint--keys">{{ __('experience.hint_keys') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        (() => {
            const space = document.querySelector('[data-xp]');
            space.classList.add('is-loading');
            setTimeout(() => {
                if (!space.classList.contains('is-ready')) {
                    space.classList.remove('is-loading');
                    space.classList.add('is-unsupported');
                }
            }, 20000);
        })();
    </script>

    <section class="bp-section xp-list" aria-labelledby="xp-list-title">
        <div class="bp-container">
            <x-section-header id="xp-list-title" :eyebrow="__('experience.eyebrow')" :title="__('experience.legend')" />

            <div class="xp-list__grid">
                @foreach ($categories as $category)
                    <div style="--star-color: {{ $category['color'] }}">
                        <h3><span class="xp-dot" aria-hidden="true"></span>{{ $category['name'] }}</h3>
                        <ul>
                            @foreach ($category['skills'] as $skill)
                                <li>
                                    <span>{{ $skill['name'] }}</span>
                                    <span class="xp-level" role="img" aria-label="{{ __('experience.level', ['level' => $skill['weight']]) }}">{!! str_repeat('●', $skill['weight']) !!}<span>{!! str_repeat('●', 5 - $skill['weight']) !!}</span></span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <script type="application/json" id="xp-data">@json($categories)</script>
@endsection
