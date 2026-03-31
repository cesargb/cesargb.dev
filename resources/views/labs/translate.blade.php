@extends('components.layout')


@section('title', __('labs_translate.meta_title'))
@section('description', __('labs_translate.meta_description'))

@section('head')
    <style>
        main > header {
            display: none;
        }

        #translator-lab {
            color: #d6dde8;
            padding: 72px 0 96px;
            background:
                radial-gradient(120px 600px at 50% -10%, rgba(55, 80, 132, 0.35), transparent 60%),
                radial-gradient(90px 520px at 80% 20%, rgba(24, 35, 64, 0.45), transparent 55%),
                #0a0a0a;
            min-height: 100vh;
        }

        #translator-lab .lab-container {
            width: min(1120px, 90vw);
            margin: 0 auto;
        }

        #translator-lab .lab-hero {
            text-align: center;
            margin-bottom: 48px;
        }

        #translator-lab .lab-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 18px;
            border-radius: 999px;
            border: 1px solid rgba(245, 120, 96, 0.35);
            background: rgba(245, 120, 96, 0.12);
            color: #f38b73;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            font-weight: 600;
            margin-bottom: 20px;
        }

        #translator-lab .lab-title {
            font-size: clamp(2.4rem, 4vw, 3.4rem);
            font-weight: 700;
            line-height: 1.05;
            color: #f3f6fb;
            margin: 0 0 16px;
        }

        #translator-lab .lab-title .accent {
            color: #f5866b;
        }

        #translator-lab .lab-subtitle {
            max-width: 720px;
            margin: 0 auto;
            color: #9aa7bd;
            font-size: 1.05rem;
            line-height: 1.6;
        }

        #translator-lab .lab-panel {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
            gap: 32px;
            align-items: center;
            margin: 56px 0 64px;
        }

        #translator-lab .panel-card {
            background: rgba(20, 28, 48, 0.72);
            border: 1px solid rgba(106, 126, 165, 0.25);
            border-radius: 20px;
            padding: 22px 22px 18px;
            box-shadow: 0 24px 40px rgba(7, 12, 24, 0.45);
            min-height: 300px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        #translator-lab .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.85rem;
            letter-spacing: 0.06em;
            color: #9aa7bd;
            text-transform: uppercase;
            font-weight: 600;
        }

        #translator-lab .panel-chip {
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid rgba(120, 140, 180, 0.35);
            color: #a8b5cc;
            font-size: 0.7rem;
            text-transform: none;
            letter-spacing: 0.02em;
        }

        #translator-lab textarea {
            flex: 1;
            width: 100%;
            background: transparent;
            border: none;
            color: #cdd6e3;
            resize: none;
            font-size: 1rem;
            line-height: 1.6;
            outline: none;
        }

        #translator-lab textarea::placeholder {
            color: #6d7a91;
        }

        #translator-lab textarea:disabled {
            color: #a8b2c4;
        }

        #translator-lab .panel-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            color: #6d7a91;
        }

        #translator-lab .panel-clear {
            background: transparent;
            border: none;
            color: #6d7a91;
            padding: 0;
            font-size: 0.8rem;
            cursor: pointer;
            text-align: right;
        }

        #translator-lab .panel-clear:hover {
            color: #cdd6e3;
        }

        #translator-lab .panel-status {
            font-size: 0.7rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #5e6a83;
        }

        #translator-lab .panel-status span {
            color: #9aa7bd;
            margin-left: 6px;
        }

        #translator-lab .panel-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
        }

        #translator-lab .translate-btn {
            background: linear-gradient(135deg, #f27562, #f49b6d);
            color: #fff;
            border: none;
            padding: 14px 32px;
            border-radius: 999px;
            font-size: 1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(244, 120, 96, 0.35);
        }

        #translator-lab .translate-btn span {
            font-size: 1.2rem;
        }

        #translator-lab .translate-btn:hover {
            filter: brightness(1.03);
        }

        #translator-lab .language-pair {
            color: #7b889f;
            font-style: italic;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        #translator-lab .language-pair span {
            font-style: normal;
        }

        #translator-lab .lab-foot {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr);
            gap: 40px;
            padding-top: 24px;
            border-top: 1px solid rgba(96, 110, 144, 0.35);
        }

        #translator-lab .foot-card h3 {
            margin: 0 0 12px;
            font-size: 1.05rem;
            color: #f38b73;
            display: inline-flex;
            gap: 10px;
            align-items: center;
        }

        #translator-lab .foot-card p {
            margin: 0;
            color: #9aa7bd;
            line-height: 1.6;
        }

        #translator-lab .foot-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 10px;
        }

        #translator-lab .foot-card a {
            color: #cdd6e3;
            text-decoration: none;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        #translator-lab .foot-card a:hover {
            color: #f38b73;
        }

        #translator-lab .link-icon {
            font-size: 0.85rem;
            color: #7d8aa2;
        }

        @media (max-width: 900px) {
            #translator-lab .lab-panel {
                grid-template-columns: 1fr;
            }

            #translator-lab .panel-action {
                padding: 12px 0 8px;
            }

            #translator-lab .panel-card {
                min-height: 240px;
            }

            #translator-lab .lab-foot {
                grid-template-columns: 1fr;
            }

            #translator-lab .lab-panel .panel-card:first-of-type {
                order: 0;
            }

            #translator-lab .lab-panel .panel-action {
                order: 1;
            }

            #translator-lab .lab-panel .panel-card:last-of-type {
                order: 2;
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
    <section id="translator-lab">
        <div class="lab-container">
            <div class="lab-hero">
                <span class="lab-badge">{{ __('labs_translate.badge') }}</span>
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
                    <button type="submit" class="translate-btn">{{ __('labs_translate.btn_translate') }} <span>&rarr;</span></button>
                    <div class="language-pair">
                        {{ __('labs_translate.language_pair_left') }}
                        <span>&harr;</span>
                        {{ __('labs_translate.language_pair_right') }}
                    </div>
                </div>

                <div class="panel-card">
                    <div class="panel-header">
                        <span>{{ __('labs_translate.result_label') }}</span>
                        <span class="panel-chip">&nbsp;</span>
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
                    <h3>
                        <span class="link-icon">●</span>
                        {{ __('labs_translate.about_title') }}
                    </h3>
                    <p>{{ __('labs_translate.about_body') }}</p>
                </div>
                <div class="foot-card">
                    <h3>{{ __('labs_translate.sources_title') }}</h3>
                    <ul>
                        <li>
                            <a href="https://developer.mozilla.org/en-US/docs/Web/API/Translator" target="_blank">
                                {{ __('labs_translate.mdn_label') }}
                                <span class="link-icon">↗</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://chromestatus.com/feature/5172811302961152" target="_blank">
                                {{ __('labs_translate.chrome_status_label') }}
                                <span class="link-icon">↗</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
