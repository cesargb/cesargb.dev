<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;

class SitemapControllerTest extends TestCase
{
    public function test_sitemap_is_served_as_xml(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false)
            ->assertSee('<urlset', false);
    }

    public function test_sitemap_lists_every_localized_page(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk();

        foreach (['en.index', 'es.index', 'en.lab.js-api-translate', 'es.lab.js-api-translate'] as $routeName) {
            $response->assertSee('<loc>'.route($routeName).'</loc>', false);
        }
    }

    public function test_sitemap_exposes_hreflang_alternates_for_each_url(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('<xhtml:link rel="alternate" hreflang="en" href="'.route('en.index').'"/>', false)
            ->assertSee('<xhtml:link rel="alternate" hreflang="es" href="'.route('es.index').'"/>', false)
            ->assertSee('<xhtml:link rel="alternate" hreflang="en" href="'.route('en.lab.js-api-translate').'"/>', false)
            ->assertSee('<xhtml:link rel="alternate" hreflang="es" href="'.route('es.lab.js-api-translate').'"/>', false);
    }
}
