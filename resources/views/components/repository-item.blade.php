@props(['repository', 'index' => 1])
@php($locale = app()->getLocale())
<a
    class="bp-card"
    href="https://github.com/{{ $repository['owner'] }}/{{ $repository['repository'] }}"
    target="_blank"
    rel="noopener"
>
    <div class="bp-card__head">
        <div class="bp-card__repo">
            <span class="bp-label">MOD-{{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }}</span>
            <h3 class="bp-card__name">{{ $repository['owner'] }}/<b>{{ $repository['repository'] }}</b></h3>
        </div>
        <span class="bp-card__arrow" aria-hidden="true">
            {!! file_get_contents(resource_path('icons/external.svg')) !!}
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
