@props(['job'])
@php($locale = app()->getLocale())
<li class="bp-job">
    <span class="bp-label bp-job__years">
        {{ $job['from'] }} — {{ $job['to'] ?? __('page-index.career_present') }}
    </span>

    <div class="bp-job__body">
        <div class="bp-job__head">
            <h3>
                @if ($job['url'])
                    <a href="{{ $job['url'] }}" target="_blank" rel="noopener">{{ $job['company'] }}</a>
                @else
                    {{ $job['company'] }}
                @endif
            </h3>
            <span class="bp-job__role">{{ $job['role'][$locale] }}</span>
        </div>

        <p>{{ $job['summary'][$locale] }}</p>

        <ul>
            @foreach ($job['highlights'][$locale] as $highlight)
                <li>{{ $highlight }}</li>
            @endforeach
        </ul>
    </div>
</li>
