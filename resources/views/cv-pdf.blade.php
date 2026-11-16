<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="UTF-8" />
        <title>{{ __('cv.meta_title') }}</title>
        <style>
            @page {
                margin: 18mm 16mm 20mm;
            }

            body {
                font-family: "DejaVu Sans", sans-serif;
                font-size: 9.5pt;
                line-height: 1.45;
                color: #27272a;
                background: #ffffff;
            }

            .photo {
                float: right;
                width: 26mm;
                height: 26mm;
                margin: 0 0 4mm 6mm;
                border-radius: 13mm;
            }

            h1 {
                margin: 0;
                font-size: 22pt;
                line-height: 1.1;
                color: #0a0a0b;
            }

            h1 + p {
                margin: 3pt 0 0;
                font-size: 11pt;
                color: #e5484d;
            }

            h1 + p strong {
                color: #e5484d;
            }

            h1 + p + p {
                margin-top: 4pt;
                color: #52525b;
            }

            h2 {
                margin: 16pt 0 6pt;
                padding-bottom: 3pt;
                border-bottom: 1.2pt solid #ff5f6d;
                font-size: 9pt;
                letter-spacing: 1pt;
                text-transform: uppercase;
                color: #0a0a0b;
            }

            h3 {
                margin: 10pt 0 0;
                font-size: 10.5pt;
                color: #0a0a0b;
                page-break-after: avoid;
            }

            h3 + p {
                margin: 1pt 0 0;
                font-size: 8.5pt;
                color: #71717a;
            }

            p {
                margin: 4pt 0 0;
            }

            ul {
                margin: 4pt 0 0;
                padding-left: 14pt;
            }

            li {
                margin: 0 0 2pt;
            }

            strong {
                color: #0a0a0b;
            }

            a {
                color: #0a0a0b;
                text-decoration: none;
            }

            footer {
                position: fixed;
                bottom: -12mm;
                left: 0;
                right: 0;
                font-size: 7.5pt;
                color: #a1a1aa;
                text-align: right;
            }
        </style>
    </head>
    <body>
        <footer>{{ preg_replace('#^https?://#', '', route(app()->getLocale().'.cv')) }}</footer>
        @if ($photo)
        <img class="photo" src="{{ $photo }}" alt="César García" />
        @endif
        {!! $html !!}
    </body>
</html>
