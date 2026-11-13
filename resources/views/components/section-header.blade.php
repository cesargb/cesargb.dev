@props(['eyebrow', 'title', 'id' => null])
<header class="bp-section-header">
    <span class="bp-label">{{ $eyebrow }}</span>
    <h2 @if ($id) id="{{ $id }}" @endif>{{ $title }}</h2>
    @if ($slot->isNotEmpty())
        <p>{{ $slot }}</p>
    @endif
</header>
