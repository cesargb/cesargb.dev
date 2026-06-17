<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;

class LanguageMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_root_defaults_to_english_without_accept_language(): void
    {
        $this->get('/')
            ->assertStatus(301)
            ->assertRedirect('/en')
            ->assertHeader('X-Robots-Tag', 'noindex');
    }

    public function test_root_falls_back_to_english_for_unsupported_language(): void
    {
        $this->withHeaders(['Accept-Language' => 'fr-FR,fr;q=0.9,de;q=0.8'])
            ->get('/')
            ->assertRedirect('/en');
    }

    public function test_root_redirects_to_spanish_for_a_spanish_browser(): void
    {
        $this->withHeaders(['Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8'])
            ->get('/')
            ->assertRedirect('/es');
    }

    public function test_root_redirects_to_spanish_for_a_region_only_header(): void
    {
        // Symfony normalises "es-ES" to "es_ES"; the locale must still resolve to "es".
        $this->withHeaders(['Accept-Language' => 'es-ES'])
            ->get('/')
            ->assertRedirect('/es');
    }

    public function test_root_redirects_to_english_for_a_region_only_header(): void
    {
        $this->withHeaders(['Accept-Language' => 'en-US'])
            ->get('/')
            ->assertRedirect('/en');
    }

    public function test_root_honours_quality_values_over_header_order(): void
    {
        // English is listed first but Spanish has the higher quality value.
        $this->withHeaders(['Accept-Language' => 'en;q=0.5,es;q=0.9'])
            ->get('/')
            ->assertRedirect('/es');
    }

    public function test_language_page_exposes_canonical_for_its_locale(): void
    {
        $this->get('/es')
            ->assertOk()
            ->assertSeeHtml('<link rel="canonical" href="'.route('es.index').'" />');
    }
}
