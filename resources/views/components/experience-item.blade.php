@props(['experience', 'index' => 1])
@php($key = $experience['key'])
@php($featured = $experience['featured'] ?? false)
<li @class(['bp-stack-card', 'bp-stack-card--featured' => $featured])>
    <div class="bp-stack-card__head">
        <span class="bp-label">{{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }} / {{ __("main.stack_{$key}_label") }}</span>
        <span @class(['bp-stack-card__tag', 'bp-stack-card__tag--accent' => $featured])>{{ __("main.stack_{$key}_tag") }}</span>
    </div>

    <h3 class="bp-stack-card__title">{{ __("main.stack_{$key}_title") }}</h3>
    <p class="bp-stack-card__description">{{ __("main.stack_{$key}_description") }}</p>

    <ul class="bp-techs" role="list">
        @foreach ($experience['items'] as $item)
            <li @class(['bp-tech', 'bp-tech--highlight' => $item['highlight'] ?? false])>
                @isset($item['mark'])
                    <span class="bp-tech__mark" aria-hidden="true">{{ $item['mark'] }}</span>
                @endisset
                {{ isset($item['label']) ? __("main.{$item['label']}") : $item['name'] }}
            </li>
        @endforeach
    </ul>

    @isset($experience['more'])
        <details class="bp-stack-card__more">
            <summary>
                {{ __("main.stack_{$key}_more") }}
                <span class="bp-stack-card__toggle" aria-hidden="true"></span>
            </summary>

            @if ($experience['more']['type'] === 'chips')
                <ul class="bp-techs" role="list">
                    @foreach ($experience['more']['items'] as $name)
                        <li class="bp-tech">{{ $name }}</li>
                    @endforeach
                </ul>
            @else
                <p class="bp-stack-card__list">{{ implode(' · ', $experience['more']['items']) }}</p>
            @endif
        </details>
    @endisset
</li>
