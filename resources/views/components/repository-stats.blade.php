@props(['repository'])
@php
    $stats = app(\App\Packagist\Stats::class)->for("{$repository['owner']}/{$repository['repository']}");
    $compact = static fn (int $n): string => match (true) {
        $n >= 1_000_000 => rtrim(rtrim(number_format($n / 1_000_000, 1), '0'), '.').'M',
        $n >= 1_000 => round($n / 1_000).'k',
        default => (string) $n,
    };
@endphp
@if ($stats)
    <dl {{ $attributes->class('bp-stats') }}>
        <div title="{{ number_format($stats['github_starts']) }}">
            <dt>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <span class="sr-only">{{ __('open-source.stars') }}</span>
            </dt>
            <dd>{{ $compact($stats['github_starts']) }}</dd>
        </div>
        <div title="{{ number_format($stats['downloads']) }}">
            <dt>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                <span class="sr-only">{{ __('open-source.downloads') }}</span>
            </dt>
            <dd>{{ $compact($stats['downloads']) }}</dd>
        </div>
    </dl>
@endif
