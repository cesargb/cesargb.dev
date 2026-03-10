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
}
