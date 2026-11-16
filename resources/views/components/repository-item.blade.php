@props(['repository', 'index' => 1, 'href' => null])
@php
    $locale = app()->getLocale();
    $external = $href === null;
    $href ??= "https://github.com/{$repository['owner']}/{$repository['repository']}";
@endphp
<a
    class="bp-card"
    href="{{ $href }}"
    @if ($external) target="_blank" rel="noopener" @endif
>
    <div class="bp-card__head">
        <div class="bp-card__repo">
            <span class="bp-label">MOD-{{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }}</span>
            <h3 class="bp-card__name">{{ $repository['owner'] }}/<b>{{ $repository['repository'] }}</b></h3>
        </div>
        <span class="bp-card__arrow" aria-hidden="true">
            @if ($external)
                {!! file_get_contents(resource_path('icons/external.svg')) !!}
            @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            @endif
        </span>
    </div>

    <p>{{ $repository['summary'][$locale] }}</p>

    <ul>
        @foreach ($repository['uses'][$locale] as $use)
            <li>{{ $use }}</li>
        @endforeach
    </ul>

    <div class="bp-card__tags">
        @foreach ($repository['tags'] as $tag)
            <span class="bp-tag">{{ $tag }}</span>
        @endforeach
    </div>
</a>
