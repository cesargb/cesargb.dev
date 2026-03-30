@extends('components.layout')


@section('title', 'Laboratorio: Experimentando con la API para traducir texto desde Javascript')
@section('description', 'Ejemplo de uso de la API de traducción desde Javascript, detectando el idioma de entrada automáticamente y traduciendo al otro idioma (español <=> inglés).')

@section('head')
    <style>
        #translator-lab form {
            display: flex;
            flex-direction: column;
            gap: 1em;
            margin: 3em auto;
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
            Ejemplo de uso de la API de traducción desde Javascript, detectando el idioma de entrada automáticamente
            y traduciendo al otro idioma <code>español <=> inglés</code>.
        </p>
        <form onsubmit="event.preventDefault(); run();">
            <textarea id="source" rows="10" placeholder="Escribe aquí el texto a traducir, la API detectará automáticamente el idioma ..." autofocus></textarea>

            <textarea id="target" placeholder="Resultado" rows="10" disabled></textarea>

            <button type="submit">Traducir</button>
        </form>

        <p>
            <small>Esta API es una está en fase Experimental, implementado en chrome a partir de la version 138</small>
        </p>
        <p>
            Fuentes:
            <ul>
                <li>
                    <a href="https://developer.mozilla.org/en-US/docs/Web/API/Translator" target="_blank">MDN Docs</a>
                </li>
                <li>
                    <a href="https://chromestatus.com/feature/5172811302961152" target="_blank">Chrome Platform Status</a>
                </li>
            </ul>
        </p>

    </section>

@endsection
