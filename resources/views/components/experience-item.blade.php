@props(['experience'])
<li class="bp-layer">
    <div class="bp-layer__head">
        <span class="bp-label">{{ $experience['layer'] }}</span>
        <h3>{{ __("main.{$experience['title']}") }}</h3>
    </div>

    <ul class="bp-layer__chips">
        @foreach ($experience['items'] as $item)
            @php($filled = $item['level'] === 'expert' ? 3 : 2)
            <li class="bp-chip">
                <span class="bp-chip__mark" aria-hidden="true">{{ $item['mark'] }}</span>
                <span class="bp-chip__body">
                    <span class="bp-chip__name">{{ $item['name'] }}</span>
                    <span class="bp-level bp-level--{{ $item['level'] }}">
                        <i aria-hidden="true">@for ($i = 0; $i < 3; $i++)<b></b>@endfor</i>
                        {{ __("main.experiences_{$item['level']}") }}
                    </span>
                </span>
            </li>
        @endforeach
    </ul>
</li>
