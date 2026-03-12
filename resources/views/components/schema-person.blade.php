@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => 'César García',
        'jobTitle' => __('main.schema_person_job_title'),
        'description' => __('main.schema_person_description'),
        'image' => 'https://www.cesargb.dev/assets/images/profile.webp?w=500&h=500&fit=crop',
        'url' => 'https://www.cesargb.dev',
        'worksFor' => [
            '@type' => 'Organization',
            'name' => 'Descom.es',
        ],
        'knowsAbout' => __('main.schema_person_knows_about'),
        'sameAs' => [
            'https://github.com/cesargb',
        ],
    ];
@endphp
<script type="application/ld+json">
    {!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
