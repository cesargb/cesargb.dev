@props([,'title', 'experiences'])
<div class="item">
    <header>
        <div>
            <div class="title">
                {!! file_get_contents(resource_path('icons/github.svg')) !!}
                <a
                    href="https://www.github.com/cesargb/{{ $repository['repository'] }}"
                    target="_blank"
                >
                    <h3>
                        cesargb/{{ $repository["repository"] }}
                    </h3>
                </a>
            </div>
            <div class="tags">
                @foreach($repository['tags'] as $tag)
                <span> {{ $tag }} </span>
                @endforeach
            </div>
        </div>

        <div>
            {!! file_get_contents(resource_path('icons/external.svg')) !!}
        </div>
    </header>


    <div class="description">
        {!! Str::markdown($repository["description"][app()->getLocale()]) !!}
    </div>
</div>
