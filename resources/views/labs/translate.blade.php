@extends('components.layout')


@section('title', __('labs_translate.meta_title'))
@section('description', __('labs_translate.meta_description'))

@section('head')
    <style>
        #translator-lab {
            padding: var(--space-24) 0;
        }

        #translator-lab .lab-container {
            width: 100%;
            max-width: var(--container);
            margin: 0 auto;
            padding: 0 var(--space-6);
        }

        #translator-lab .lab-hero {
            display: grid;
            gap: var(--space-4);
            justify-items: start;
            margin-bottom: var(--space-12);
        }

        #translator-lab .lab-title {
            font-size: 48px;
            line-height: 54px;
            font-weight: 700;
            letter-spacing: -0.03em;
            max-width: 20ch;
        }

        #translator-lab .lab-title .accent {
            color: var(--accent);
        }

        #translator-lab .lab-subtitle {
            max-width: var(--measure);
            color: var(--ink-muted);
            font-size: 18px;
            line-height: 28px;
        }

        #translator-lab .lab-panel {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
            gap: var(--space-6);
            align-items: center;
            margin-bottom: var(--space-12);
        }

        #translator-lab .panel-card {
            display: flex;
            flex-direction: column;
            gap: var(--space-4);
            min-height: 300px;
            padding: var(--space-6);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            transition: border-color var(--dur-fast) var(--ease-out);
        }

        #translator-lab .panel-card:focus-within {
            border-color: var(--border-strong);
        }

        #translator-lab .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--space-3);
            font: 500 12px/16px var(--font-mono);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink-subtle);
        }

        #translator-lab .panel-chip {
            padding: 2px var(--space-2);
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: var(--surface-raised);
            color: var(--ink-muted);
            text-transform: lowercase;
            letter-spacing: 0;
        }

        #translator-lab .panel-chip:empty,
        #translator-lab .panel-chip--empty {
            visibility: hidden;
        }

        #translator-lab textarea {
            flex: 1;
            width: 100%;
            padding: 0;
            background: transparent;
            border: none;
            outline: none;
            color: var(--ink);
            font: 400 16px/26px var(--font-sans);
            resize: none;
        }

        #translator-lab textarea::placeholder {
            color: var(--ink-subtle);
        }

        #translator-lab textarea:disabled {
            color: var(--ink-muted);
        }

        #translator-lab .panel-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--space-3);
            padding-top: var(--space-3);
            border-top: 1px dashed var(--border);
            font-size: 14px;
            color: var(--ink-subtle);
        }

        #translator-lab .panel-clear {
            min-height: 32px;
            padding: 0 var(--space-2);
            background: transparent;
            border: none;
            border-radius: var(--radius-sm);
            color: var(--ink-subtle);
            font: 500 14px/1 var(--font-sans);
            cursor: pointer;
        }

        #translator-lab .panel-clear:hover {
            color: var(--ink);
            background: var(--surface-raised);
        }

        #translator-lab .panel-status {
            font: 500 12px/16px var(--font-mono);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink-subtle);
        }

        #translator-lab .panel-status span {
            color: var(--ink);
            margin-left: var(--space-1);
        }

        #translator-lab .panel-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: var(--space-3);
        }

        #translator-lab .language-pair {
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
            font: 500 12px/16px var(--font-mono);
            color: var(--ink-subtle);
        }

        #translator-lab .lab-foot {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr);
            gap: var(--space-12);
            padding-top: var(--space-8);
            border-top: 1px solid var(--border);
        }

        #translator-lab .foot-card h3 {
            margin: 0 0 var(--space-3);
            font-size: 18px;
            line-height: 26px;
            font-weight: 600;
            color: var(--ink);
        }

        #translator-lab .foot-card p {
            color: var(--ink-muted);
        }

        #translator-lab .foot-card ul {
            display: grid;
            gap: var(--space-2);
            margin: 0;
            padding: 0;
            list-style: none;
        }

        #translator-lab .foot-card a {
            color: var(--ink-muted);
            font-weight: 500;
        }

        #translator-lab .foot-card a:hover {
            color: var(--accent);
        }

        @media (max-width: 900px) {
            #translator-lab .lab-panel {
                grid-template-columns: 1fr;
            }

            #translator-lab .panel-card {
                min-height: 220px;
            }

            #translator-lab .lab-foot {
                grid-template-columns: 1fr;
                gap: var(--space-8);
            }
        }

        @media (max-width: 640px) {
            #translator-lab {
                padding: var(--space-12) 0;
            }

            #translator-lab .lab-container {
                padding: 0 var(--space-4);
            }

            #translator-lab .lab-title {
                font-size: 34px;
                line-height: 40px;
            }
        }
    </style>

    <script>
        const translateStrings = {
            detectError: @json(__('labs_translate.alert_detect_error')),
            supportError: @json(__('labs_translate.alert_support_error')),
            emptyError: @json(__('labs_translate.alert_empty_error')),
            translating: @json(__('labs_translate.status_translating')),
            idle: @json(__('labs_translate.status_idle')),
        };

        const langsSupported = ['en', 'es'];

        function hasTranslationSupport() {
            return 'Translator' in window && 'LanguageDetector' in window;
        }

        async function langDetector(text) {
            const detector =await LanguageDetector.create({
                expectedInputLanguages: langsSupported,
            });

            const langExpected =  await detector.detect(text)

            for (const lang of langExpected) {
                if (langsSupported.includes(lang.detectedLanguage)) {
                    return lang.detectedLanguage;
                }
            }

            return null;
        }

        async function translate(text) {
            const langSource = await langDetector(text);

            if (!langSource) {
                alert(translateStrings.detectError);
                return null;
            }

            const translator = await Translator.create({
                sourceLanguage: langSource,
                targetLanguage: langSource === langsSupported[0] ? langsSupported[1] : langsSupported[0],
            });

            const paragraphs = await Promise.all(text.split('\n').map(p => p.trim() === '' ? '' : translator.translate(p)));

            const target = paragraphs.join('\n');

            return target;
        }

        async function run() {
            if (!hasTranslationSupport()) {
                alert(translateStrings.supportError);
                return;
            }

            const source = document.getElementById('source');
            const target = document.getElementById('target');
            const status = document.getElementById('status-label');

            if (source.value === '') {
                alert(translateStrings.emptyError);
                return;
            }

            target.value = translateStrings.translating;
            status.textContent = translateStrings.translating;

            const translation = await translate(source.value);

            target.value = translation || '';
            status.textContent = translation ? translateStrings.idle : translateStrings.idle;
        }

        function clearSource() {
            const source = document.getElementById('source');
            const target = document.getElementById('target');
            const status = document.getElementById('status-label');

            source.value = '';
            target.value = '';
            status.textContent = translateStrings.idle;
            source.focus();
        }
    </script>
@endsection

@section('main')
    <section id="translator-lab" class="bp-grid">
        <div class="lab-container">
            <div class="lab-hero">
                <span class="bp-label bp-status">{{ __('labs_translate.badge') }}</span>
                <h1 class="lab-title">
                    {{ __('labs_translate.title_prefix') }}
                    <span class="accent">{{ __('labs_translate.title_accent') }}</span>
                </h1>
                <p class="lab-subtitle">{{ __('labs_translate.intro') }}</p>
            </div>

            <form class="lab-panel" onsubmit="event.preventDefault(); run();">
                <div class="panel-card">
                    <div class="panel-header">
                        <span>{{ __('labs_translate.input_label') }}</span>
                        <span class="panel-chip">{{ __('labs_translate.auto_detect') }}</span>
                    </div>
                    <textarea id="source" rows="10" placeholder="{{ __('labs_translate.placeholder_source') }}" autofocus></textarea>
                    <div class="panel-footer">
                        <span></span>
                        <button type="button" class="panel-clear" onclick="clearSource()">{{ __('labs_translate.clear_text') }}</button>
                    </div>
                </div>

                <div class="panel-action">
                    <button type="submit" class="bp-btn bp-btn--primary">{{ __('labs_translate.btn_translate') }} <span aria-hidden="true">&rarr;</span></button>
                    <div class="language-pair">
                        {{ __('labs_translate.language_pair_left') }}
                        <span aria-hidden="true">&harr;</span>
                        {{ __('labs_translate.language_pair_right') }}
                    </div>
                </div>

                <div class="panel-card">
                    <div class="panel-header">
                        <span>{{ __('labs_translate.result_label') }}</span>
                        <span class="panel-chip panel-chip--empty" aria-hidden="true">&nbsp;</span>
                    </div>
                    <textarea id="target" placeholder="{{ __('labs_translate.placeholder_target') }}" rows="10" disabled></textarea>
                    <div class="panel-footer">
                        <span></span>
                        <div class="panel-status">{{ __('labs_translate.status_system') }} <span id="status-label">{{ __('labs_translate.status_idle') }}</span></div>
                    </div>
                </div>
            </form>

            <div class="lab-foot">
                <div class="foot-card">
                    <h3>{{ __('labs_translate.about_title') }}</h3>
                    <p>{{ __('labs_translate.about_body') }}</p>
                </div>
                <div class="foot-card">
                    <h3>{{ __('labs_translate.sources_title') }}</h3>
                    <ul>
                        <li>
                            <a href="https://developer.mozilla.org/en-US/docs/Web/API/Translator" target="_blank" rel="noopener">
                                {{ __('labs_translate.mdn_label') }}
                                <span aria-hidden="true">↗</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://chromestatus.com/feature/5172811302961152" target="_blank" rel="noopener">
                                {{ __('labs_translate.chrome_status_label') }}
                                <span aria-hidden="true">↗</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
