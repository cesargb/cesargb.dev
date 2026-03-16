@props(['experience'])

@php
    $iconMap = [
        'PHP' => ['type' => 'svg', 'path' => 'icons/php.svg'],
        'Laravel' => ['type' => 'emoji', 'value' => '🔥'],
        'Symfony' => ['type' => 'emoji', 'value' => '🧩'],
        'Node.js' => ['type' => 'emoji', 'value' => '🟢'],
        'Python' => ['type' => 'emoji', 'value' => '🐍'],
        'Django' => ['type' => 'emoji', 'value' => '🎯'],
        'HTML/CSS' => ['type' => 'emoji', 'value' => '🎨'],
        'JavaScript' => ['type' => 'emoji', 'value' => '⚡'],
        'Vue.js' => ['type' => 'emoji', 'value' => '🟩'],
        'AWS' => ['type' => 'svg', 'path' => 'icons/aws.svg'],
        'Linux' => ['type' => 'svg', 'path' => 'icons/linux.svg'],
        'Docker' => ['type' => 'emoji', 'value' => '🐳'],
    ];
@endphp

<div class="item experience-card">
    <header>
        <div class="title">
            <div class="icon-h">
                {!! file_get_contents(resource_path('icons/technology.svg')) !!}
            </div>
            <h3>{{ __("main.{$experience['title']}") }}</h3>
        </div>
    </header>

    <div class="experience-list">
        @foreach($experience['items'] as $item)
            @php
                $icon = $iconMap[$item['name']] ?? null;
                $levelKey = "main.experiences_{$item['level']}";
            @endphp
            <div class="experience-list__item">
                <div class="experience-list__name">
                    <span class="experience-icon" aria-hidden="true">
                        @if($icon && $icon['type'] === 'svg')
                            {!! file_get_contents(resource_path($icon['path'])) !!}
                        @elseif($icon && $icon['type'] === 'emoji')
                            {{ $icon['value'] }}
                        @else
                            *
                        @endif
                    </span>
                    <span>{{ $item['name'] }}</span>
                </div>
                <div class="experience-list__level">
                    {{ __($levelKey) }}
                </div>
            </div>
        @endforeach
    </div>
</div>
