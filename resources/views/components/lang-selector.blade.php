@php
    $current = app()->getLocale();
    $alternates = Context::getHidden('meta.hreflang') ?? [];
@endphp
<div class="bp-lang" role="group" aria-label="{{ __('main.change_lang') }}">
    @foreach (['en', 'es'] as $lang)
        <a
            href="{{ $alternates[$lang] ?? '/'.$lang }}"
            hreflang="{{ $lang }}"
            lang="{{ $lang }}"
            @if ($lang === $current) aria-current="true" @endif
        >{{ $lang }}</a>
    @endforeach
</div>
