@extends('components.layout')


@section('title', 'Laboratorio: Experimentando con el traductor de API por Javascript')
@section('description', 'Ejemplo de uso del traductor de API por Javascript para traducir texto entre inglés y español, detectando automáticamente el idioma de entrada.')

@section('head')
    <style>
        #translator-lab form {
            display: flex;
            flex-direction: column;
            gap: 1em;
        }
    </style>

    <script>
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
            Ejemplo para traductir texto <code>es</code> <=> <code>en</code>, detectaremos el idioma de entrada automáticamente y
            traduciremos al otro idioma. El resultado se mostrará en un segundo textarea.
        </p>
        <form onsubmit="event.preventDefault(); run();">
            <textarea id="source" placeholder="Texto de prueba" rows="10" placeholder="Escribe aquí el texto a traducir ..." autofocus></textarea>

            <textarea id="target" placeholder="Resultado" rows="10" disabled></textarea>

            <button type="submit">Traducir</button>
        </form>

    </section>

@endsection
