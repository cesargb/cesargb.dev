<?php

namespace Tests\Feature;

use App\Data\Skills;
use Tests\TestCase;

class ExperiencePageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_experience_pages_return_successful_response(): void
    {
        $this->get('/en/experience')->assertOk()->assertSee('A journey through my experience');
        $this->get('/es/experiencia')->assertOk()->assertSee('Un viaje por mi experiencia');
    }

    public function test_experience_pages_show_a_preview_while_the_scene_loads(): void
    {
        $this->get('/en/experience')
            ->assertSeeHtml('<div class="xp-preview" aria-hidden="true">')
            ->assertSee('Preparing the journey…');

        $this->get('/es/experiencia')
            ->assertSeeHtml('<div class="xp-preview" aria-hidden="true">')
            ->assertSee('Preparando el viaje…');
    }

    public function test_experience_pages_expose_canonical_and_hreflang(): void
    {
        $en = route('en.experience');
        $es = route('es.experience');

        foreach (['/en/experience' => $en, '/es/experiencia' => $es] as $path => $canonical) {
            $this->get($path)
                ->assertOk()
                ->assertSeeHtml("<link rel=\"canonical\" href=\"{$canonical}\" />")
                ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"en\" href=\"{$en}\" />")
                ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"es\" href=\"{$es}\" />")
                ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"x-default\" href=\"{$en}\" />");
        }
    }

    public function test_experience_page_lists_every_skill_with_its_level(): void
    {
        $response = $this->get('/en/experience')->assertOk();

        foreach (Skills::forLocale('en') as $category) {
            $response->assertSee($category['name']);

            foreach ($category['skills'] as $skill) {
                $response->assertSee($skill['name']);
            }
        }

        $response->assertSeeHtml('aria-label="Level 5 of 5"');
    }

    public function test_skill_names_are_translated(): void
    {
        $this->get('/en/experience')->assertSee('Monitoring')->assertDontSee('Monitorización');
        $this->get('/es/experiencia')->assertSee('Monitorización')->assertSee('Programación');
    }

    public function test_experience_page_is_linked_from_the_navigation(): void
    {
        $this->get('/en')->assertSeeHtml('href="'.route('en.experience').'"');
        $this->get('/es')->assertSeeHtml('href="'.route('es.experience').'"');
    }

    public function test_sitemap_lists_experience_pages(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('<loc>'.route('en.experience').'</loc>', false)
            ->assertSee('<loc>'.route('es.experience').'</loc>', false);
    }

    public function test_every_skill_has_a_weight_between_one_and_five_and_a_unique_name(): void
    {
        foreach (['en', 'es'] as $locale) {
            $names = [];

            foreach (Skills::forLocale($locale) as $category) {
                foreach ($category['skills'] as $skill) {
                    $this->assertNotSame('', $skill['name']);
                    $this->assertGreaterThanOrEqual(1, $skill['weight']);
                    $this->assertLessThanOrEqual(5, $skill['weight']);
                    $names[] = $skill['name'];
                }
            }

            $this->assertSame($names, array_unique($names), "Duplicated skill names in {$locale}");
        }
    }
}
