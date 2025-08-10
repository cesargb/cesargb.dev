<div class="item">
    <a
        href="https://www.github.com/cesargb/{{ $repository['repository'] }}"
        target="_blank"
    >
        <h3 class="title">
            cesargb/{{ $repository["repository"] }} <IconLinkRemote />
        </h3>
    </a>

    <div class="tags">
        @foreach($repository['tags'] as $tag)
        <em> {{ $tag }} </em>
        @endforeach
    </div>

    <div class="description">
        {!! Str::markdown($repository["description"][app()->getLocale()]) !!}
    </div>

    <div class="badges">
        @foreach($repository['badges'] as $badge)
        <a href="{{ $badge['url'] }}" target="_blank">
            <img src="{{ $badge['image'] }}" alt="{{ $badge['alt'] }}" />
        </a>
        @endforeach
    </div>
</div>
