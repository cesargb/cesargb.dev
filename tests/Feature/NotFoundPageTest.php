<?php

namespace Tests\Feature;

use Tests\TestCase;

class NotFoundPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_unknown_route_returns_styled_404(): void
    {
        $this->get('/this-route-does-not-exist')
            ->assertNotFound()
            ->assertSee('404')
            ->assertSee('This route threw an exception')
            ->assertSee('noindex,nofollow', false);
    }

    public function test_english_path_renders_english_404(): void
    {
        $this->get('/en/no-existe')
            ->assertNotFound()
            ->assertSee('lang="en"', false)
            ->assertSee('This route threw an exception');
    }

    public function test_spanish_path_renders_localized_404(): void
    {
        $this->get('/es/no-existe')
            ->assertNotFound()
            ->assertSee('lang="es"', false)
            ->assertSee('Esta ruta lanzó una excepción');
    }
}
