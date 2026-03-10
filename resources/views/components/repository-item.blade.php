@props(['repository'])
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
</div>
