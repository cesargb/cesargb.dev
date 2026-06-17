@php($locale = app()->getLocale())
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ __('page-404.meta_title') }}</title>
    <link rel="icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }

        @keyframes blink { 0%, 49% { opacity: 1; } 50%, 100% { opacity: 0; } }
        @keyframes floaty { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        @keyframes gridmove { from { background-position: 0 0; } to { background-position: 40px 40px; } }
        @media (prefers-reduced-motion: reduce) { * { animation: none !important; } }

        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', system-ui, sans-serif;
            background: #0a0a0a;
            color: #e8eaee;
            position: relative;
            overflow: hidden;
        }

        .grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .035) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: gridmove 8s linear infinite;
            pointer-events: none;
        }

        .glow {
            position: absolute;
            top: -220px;
            left: 50%;
            transform: translateX(-50%);
            width: 760px;
            height: 520px;
            background: radial-gradient(ellipse at center, rgba(226, 81, 81, .16), transparent 70%);
            pointer-events: none;
        }

        .site-header {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 32px;
            max-width: 1100px;
            margin: 0 auto;
            width: 100%;
        }

        .logo {
            font-weight: 800;
            letter-spacing: -.02em;
            font-size: 17px;
            color: #fff;
            text-decoration: none;
        }
        .logo span { color: #6b7686; }

        .nav-right { display: flex; align-items: center; gap: 18px; }

        .lang-group {
            display: flex;
            align-items: center;
            gap: 9px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 500;
        }
        .lang { color: #6b7686; text-decoration: none; }
        .lang-active { color: #fff; }
        .lang-sep { color: #3a3f4a; }

        .icon-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: 1px solid #232732;
            border-radius: 9px;
            color: #c4c9d4;
            text-decoration: none;
            transition: border-color .2s ease, color .2s ease;
        }
        .icon-btn:hover { border-color: #3a4150; color: #fff; }

        .main {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 32px 24px 64px;
            max-width: 760px;
            margin: 0 auto;
            width: 100%;
        }

        .eyebrow {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #6b7686;
            margin-bottom: 22px;
        }

        .code-404 {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 148px;
            line-height: .9;
            letter-spacing: -.05em;
            margin: 0;
            background: linear-gradient(180deg, #ffffff, rgb(226, 81, 81));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
        }

        .headline {
            font-weight: 700;
            font-size: 26px;
            letter-spacing: -.02em;
            margin: 26px 0 12px;
            color: #f3f5f8;
        }

        .lead {
            font-size: 16px;
            line-height: 1.6;
            color: #9097a3;
            margin: 0 0 34px;
            max-width: 460px;
        }

        .terminal {
            width: 100%;
            max-width: 540px;
            background: #111419;
            border: 1px solid #20242e;
            border-radius: 12px;
            text-align: left;
            overflow: hidden;
            box-shadow: 0 24px 60px -28px rgba(0, 0, 0, .8);
            animation: floaty 5s ease-in-out infinite;
        }

        .terminal-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 11px 15px;
            border-bottom: 1px solid #20242e;
            background: #0e1116;
        }
        .dot { width: 11px; height: 11px; border-radius: 50%; }
        .dot-red { background: #ff5f57; }
        .dot-amber { background: #febc2e; }
        .dot-green { background: #28c840; }
        .terminal-title {
            margin-left: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: #5b6473;
        }

        .terminal-body {
            margin: 0;
            padding: 18px 18px 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13.5px;
            line-height: 1.85;
            color: #c4c9d4;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .c-dim { color: #5b6473; }
        .c-cmd { color: rgb(226, 81, 81); }
        .c-path { color: #9097a3; }
        .c-http { color: #ff7b72; }
        .c-exc { color: #ffd166; }
        .c-str { color: #7ee787; }
        .cursor {
            display: inline-block;
            width: 9px;
            height: 17px;
            background: #7ee787;
            vertical-align: -3px;
            animation: blink 1.1s step-end infinite;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            justify-content: center;
            margin-top: 38px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 13px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: background .2s ease, border-color .2s ease;
        }
        .btn-primary { background: rgb(226, 81, 81); color: #fff; }
        .btn-primary:hover { background: #c83f3f; }
        .btn-secondary { background: transparent; border: 1px solid #2a2f3a; color: #e8eaee; }
        .btn-secondary:hover { border-color: #3f4654; background: #161a21; }

        .site-footer {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: #4d5564;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="grid"></div>
    <div class="glow"></div>

    <header class="site-header">
        <a href="/{{ $locale }}" class="logo">CÉSAR<span>GARCÍA</span></a>
        <div class="nav-right">
            <div class="lang-group">
                @if ($locale === 'es')
                    <a href="/en" class="lang">EN</a>
                    <span class="lang-sep">/</span>
                    <span class="lang lang-active">ES</span>
                @else
                    <span class="lang lang-active">EN</span>
                    <span class="lang-sep">/</span>
                    <a href="/es" class="lang">ES</a>
                @endif
            </div>
            <a href="https://github.com/cesargb" title="{{ __('page-index.header_github_alt') }}" class="icon-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .5C5.7.5.5 5.7.5 12c0 5.1 3.3 9.4 7.9 10.9.6.1.8-.2.8-.5v-2c-3.2.7-3.9-1.4-3.9-1.4-.5-1.3-1.3-1.7-1.3-1.7-1-.7.1-.7.1-.7 1.2.1 1.8 1.2 1.8 1.2 1 .9 2.6 1.7 4 .7.1-.6.4-1 .7-1.2-2.6-.3-5.3-1.3-5.3-5.8 0-1.3.5-2.3 1.2-3.1-.1-.3-.5-1.5.1-3.1 0 0 1-.3 3.3 1.2a11.5 11.5 0 0 1 6 0C17.3 4.9 18.3 5.2 18.3 5.2c.6 1.6.2 2.8.1 3.1.8.8 1.2 1.8 1.2 3.1 0 4.5-2.7 5.5-5.3 5.8.4.4.8 1.1.8 2.2v3.3c0 .3.2.7.8.5 4.6-1.5 7.9-5.8 7.9-10.9C23.5 5.7 18.3.5 12 .5Z"/></svg>
            </a>
        </div>
    </header>

    <main class="main">
        <div class="eyebrow">{{ __('page-404.eyebrow') }}</div>

        <h1 class="code-404">404</h1>

        <h2 class="headline">{{ __('page-404.heading') }}</h2>
        <p class="lead">{{ __('page-404.description') }}</p>

        <div class="terminal">
            <div class="terminal-bar">
                <span class="dot dot-red"></span>
                <span class="dot dot-amber"></span>
                <span class="dot dot-green"></span>
                <span class="terminal-title">~/cesargb.dev — bash</span>
            </div>
            <pre class="terminal-body"><span class="c-dim">$</span> <span class="c-cmd">curl</span> -I cesargb.dev<span class="c-path">/the-page-you-wanted</span>
<span class="c-http">HTTP/2 404</span> <span class="c-dim">Not Found</span>
<span class="c-dim">→ throw new</span> <span class="c-exc">RouteNotFoundException</span>(<span class="c-str">"page"</span>);
<span class="c-dim">$</span> <span class="cursor"></span></pre>
        </div>

        <div class="actions">
            <a href="/{{ $locale }}" class="btn btn-primary">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                {{ __('page-404.back_home') }}
            </a>
            <a href="https://github.com/cesargb" class="btn btn-secondary">{{ __('page-404.view_github') }}</a>
        </div>
    </main>

    <footer class="site-footer">
        © {{ date('Y') }} César García · {{ __('page-404.footer_role') }}
    </footer>
</div>
</body>
</html>
