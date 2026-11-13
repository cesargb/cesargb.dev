@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        '@id' => App\Cesargb::PERSON_ID,
        'name' => 'César García',
        'jobTitle' => __('main.schema_person_job_title'),
        'description' => __('main.schema_person_description'),
        'image' => App\Cesargb::PROFILE_IMAGE,
        'url' => 'https://www.cesargb.dev',
        'worksFor' => [
            '@type' => 'Organization',
            'name' => 'Descom.es',
            'url' => 'https://www.descom.es',
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
