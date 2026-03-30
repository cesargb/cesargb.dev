@extends('components.layout')


@section('title', __('labs_translate.title'))
@section('description', __('labs_translate.description'))

@section('head')
    <style>
        #translator-lab form {
            display: flex;
            flex-direction: row;
            align-items: anchor-center;
            gap: 1em;
            margin: 5em auto;
        }
    </style>

    <script>
        function hasTranslationSupport() {
            return 'Translator' in window && 'LanguageDetector' in window;
        }

        async function langDetector(text) {
            const detector =await LanguageDetector.create({
                expectedInputLanguages: ["en", "es"],
            });

            const langExpected =  await detector.detect(text)

            for (const lang of langExpected) {
                if (['en', 'es'].includes(lang.detectedLanguage)) {
                    return lang.detectedLanguage;
                }
            }

            return null;
        }

        async function translate(text) {
            const langSource = await langDetector(text);

            if (!langSource) {
                alert('Could not detect the language of the source text.');
                return null;
            }

            const translator = await Translator.create({
                sourceLanguage: langSource,
                targetLanguage: langSource === 'en' ? 'es' : 'en',
            });

            const paragraphs = await Promise.all(text.split('\n').map(p => p.trim() === '' ? '' : translator.translate(p)));

            const target = paragraphs.join('\n');

            return target;
        }

        async function run() {
            if (!hasTranslationSupport()) {
                alert('Translation API is not supported in this browser.');
                return;
            }

            const source = document.getElementById('source');
            const target = document.getElementById('target');

            if (source.value === '') {
                alert('Please, enter some text to translate.');
                return;
            }

            target.value = 'Translating...';

            target.value = await translate(source.value);
        }
    </script>
@endsection

@section('main')
    <section id="translator-lab">
        <p>
            {{ __('labs_translate.intro') }}
            <code>{{ __('labs_translate.language_pair') }}</code>.
        </p>
        <form onsubmit="event.preventDefault(); run();">
            <textarea id="source" rows="10" placeholder="{{ __('labs_translate.placeholder_source') }}" autofocus></textarea>

            <button type="submit">{{ __('labs_translate.btn_translate') }}</button>

            <textarea id="target" placeholder="{{ __('labs_translate.placeholder_target') }}" rows="10" disabled></textarea>
        </form>

        <p>
            <small>{{ __('labs_translate.experimental_notice') }}</small>
        </p>
        <p>
            {{ __('labs_translate.sources_label') }}
            <ul>
                <li>
                    <a href="https://developer.mozilla.org/en-US/docs/Web/API/Translator" target="_blank" class="external-link">{{ __('labs_translate.mdn_label') }}</a>
                </li>
                <li>
                    <a href="https://chromestatus.com/feature/5172811302961152" target="_blank" class="external-link">{{ __('labs_translate.chrome_status_label') }}</a>
                </li>
            </ul>
        </p>

    </section>

@endsection
