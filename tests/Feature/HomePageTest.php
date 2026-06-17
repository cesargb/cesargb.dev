<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->get('/')
            ->assertRedirect('/en');
    }

    public function test_the_application_returns_a_successful_response_for_spanish(): void
    {
        $this->get('/es')
            ->assertStatus(200);
    }

    public function test_root_redirects_to_spanish_when_preferred(): void
    {
        $this->withHeaders(['Accept-Language' => 'es'])
            ->get('/')
            ->assertRedirect('/es');
    }

    public function test_root_redirects_to_english_when_preferred(): void
    {
        $this->withHeaders(['Accept-Language' => 'en'])
            ->get('/')
            ->assertRedirect('/en');
    }

    public function test_root_redirect_is_not_indexable(): void
    {
        $this->get('/')
            ->assertStatus(301)
            ->assertHeader('X-Robots-Tag', 'noindex');
    }

    public function test_english_home_exposes_canonical_and_hreflang(): void
    {
        $en = route('en.index');
        $es = route('es.index');
        $default = url('/');

        $this->get('/en')
            ->assertOk()
            ->assertSeeHtml("<link rel=\"canonical\" href=\"{$en}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"en\" href=\"{$en}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"es\" href=\"{$es}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"x-default\" href=\"{$default}\" />")
            ->assertSeeHtml("<meta property=\"og:url\" content=\"{$en}\">");
    }

    public function test_spanish_home_exposes_canonical_and_hreflang(): void
    {
        $en = route('en.index');
        $es = route('es.index');
        $default = url('/');

        $this->get('/es')
            ->assertOk()
            ->assertSeeHtml("<link rel=\"canonical\" href=\"{$es}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"en\" href=\"{$en}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"es\" href=\"{$es}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"x-default\" href=\"{$default}\" />")
            ->assertSeeHtml("<meta property=\"og:url\" content=\"{$es}\">");
    }

    public function test_home_is_not_indexable_outside_production(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSeeHtml('<meta name="robots" content="noindex,nofollow" />');

        $this->get('/es')
            ->assertOk()
            ->assertSeeHtml('<meta name="robots" content="noindex,nofollow" />');
    }

    public function test_home_is_indexable_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $this->get('/en')
            ->assertOk()
            ->assertDontSee('name="robots"', false);

        $this->get('/es')
            ->assertOk()
            ->assertDontSee('name="robots"', false);
    }
}
