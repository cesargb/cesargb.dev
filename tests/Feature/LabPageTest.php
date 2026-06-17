<?php

namespace Tests\Feature;

use Tests\TestCase;

class LabPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_english_lab_page_returns_successful_response(): void
    {
        $this->get('/en/lab/js-api-translate')
            ->assertOk();
    }

    public function test_spanish_lab_page_returns_successful_response(): void
    {
        $this->get('/es/laboratorio/js-api-traduccion')
            ->assertOk();
    }

    public function test_english_lab_page_exposes_canonical_and_hreflang(): void
    {
        $en = route('en.lab.js-api-translate');
        $es = route('es.lab.js-api-translate');
        $default = url('/');

        $this->get('/en/lab/js-api-translate')
            ->assertOk()
            ->assertSeeHtml("<link rel=\"canonical\" href=\"{$en}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"en\" href=\"{$en}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"es\" href=\"{$es}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"x-default\" href=\"{$default}\" />")
            ->assertSeeHtml("<meta property=\"og:url\" content=\"{$en}\">");
    }

    public function test_spanish_lab_page_exposes_canonical_and_hreflang(): void
    {
        $en = route('en.lab.js-api-translate');
        $es = route('es.lab.js-api-translate');
        $default = url('/');

        $this->get('/es/laboratorio/js-api-traduccion')
            ->assertOk()
            ->assertSeeHtml("<link rel=\"canonical\" href=\"{$es}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"en\" href=\"{$en}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"es\" href=\"{$es}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"x-default\" href=\"{$default}\" />")
            ->assertSeeHtml("<meta property=\"og:url\" content=\"{$es}\">");
    }

    public function test_lab_pages_are_not_indexable_outside_production(): void
    {
        $this->get('/en/lab/js-api-translate')
            ->assertOk()
            ->assertSeeHtml('<meta name="robots" content="noindex,nofollow" />');

        $this->get('/es/laboratorio/js-api-traduccion')
            ->assertOk()
            ->assertSeeHtml('<meta name="robots" content="noindex,nofollow" />');
    }

    public function test_lab_pages_are_indexable_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $this->get('/en/lab/js-api-translate')
            ->assertOk()
            ->assertDontSee('name="robots"', false);

        $this->get('/es/laboratorio/js-api-traduccion')
            ->assertOk()
            ->assertDontSee('name="robots"', false);
    }

    public function test_english_lab_index_redirects_and_is_not_indexable(): void
    {
        $this->get('/en/lab')
            ->assertRedirect(route('en.lab.js-api-translate'))
            ->assertHeader('X-Robots-Tag', 'noindex');
    }

    public function test_spanish_lab_index_redirects_and_is_not_indexable(): void
    {
        $this->get('/es/laboratorio')
            ->assertRedirect(route('es.lab.js-api-translate'))
            ->assertHeader('X-Robots-Tag', 'noindex');
    }
}
