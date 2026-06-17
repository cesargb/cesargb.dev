<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;

class RobotsTxtControllerTest extends TestCase
{
    public function test_robots_txt_is_served_as_plain_text(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function test_robots_txt_disallows_crawling_outside_production(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('User-agent: *')
            ->assertSee('Disallow: /')
            ->assertDontSee('Allow: /')
            ->assertDontSee('Sitemap:');
    }

    public function test_robots_txt_allows_crawling_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('User-agent: *')
            ->assertSee('Allow: /')
            ->assertSee('Sitemap: https://www.cesargb.dev/sitemap.xml')
            ->assertDontSee('Disallow: /');
    }
}
