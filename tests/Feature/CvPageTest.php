<?php

namespace Tests\Feature;

use Tests\TestCase;

class CvPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_cv_pages_render_the_markdown_as_html(): void
    {
        $this->get('/en/cv')
            ->assertOk()
            ->assertSeeHtml('<h1>César García</h1>')
            ->assertSee('Professional experience')
            ->assertSee('cesargb@gmail.com')
            ->assertSee('Universidad de Alicante');

        $this->get('/es/cv')
            ->assertOk()
            ->assertSeeHtml('<h1>César García</h1>')
            ->assertSee('Experiencia profesional')
            ->assertSee('Diplomatura en Estadística');
    }

    public function test_cv_photo_is_shown_on_the_page_but_not_in_the_markdown(): void
    {
        $this->get('/en/cv')->assertSeeHtml('src="/assets/images/profile.webp?w=240&h=240&fit=crop"');

        $this->get('/en/cv.md')->assertDontSee('profile.webp');
    }

    public function test_cv_pages_expose_canonical_and_hreflang(): void
    {
        $en = route('en.cv');
        $es = route('es.cv');

        foreach (['/en/cv' => $en, '/es/cv' => $es] as $path => $canonical) {
            $this->get($path)
                ->assertOk()
                ->assertSeeHtml("<link rel=\"canonical\" href=\"{$canonical}\" />")
                ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"en\" href=\"{$en}\" />")
                ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"es\" href=\"{$es}\" />");
        }
    }

    public function test_cv_pages_link_to_the_markdown_source(): void
    {
        $this->get('/en/cv')
            ->assertSeeHtml('<link rel="alternate" type="text/markdown" href="'.route('en.cv.markdown').'" />');
    }

    public function test_cv_markdown_is_served_raw_and_not_indexed(): void
    {
        foreach (['en', 'es'] as $locale) {
            $response = $this->get("/{$locale}/cv.md")
                ->assertOk()
                ->assertHeader('Content-Type', 'text/markdown; charset=UTF-8')
                ->assertHeader('X-Robots-Tag', 'noindex');

            $this->assertStringStartsWith('# César García', $response->getContent());
            $this->assertSame(file_get_contents(resource_path("cv/{$locale}.md")), $response->getContent());
        }
    }

    public function test_cv_pdf_is_generated_from_the_markdown(): void
    {
        foreach (['en', 'es'] as $locale) {
            $response = $this->get("/{$locale}/cv.pdf")
                ->assertOk()
                ->assertHeader('Content-Type', 'application/pdf')
                ->assertHeader('Content-Disposition', "inline; filename=\"cesar-garcia-cv-{$locale}.pdf\"")
                ->assertHeader('X-Robots-Tag', 'noindex');

            $this->assertStringStartsWith('%PDF-', $response->getContent());
        }
    }

    public function test_cv_pages_link_to_the_pdf(): void
    {
        $this->get('/en/cv')->assertSeeHtml('href="'.route('en.cv.pdf').'"');
        $this->get('/es/cv')->assertSeeHtml('href="'.route('es.cv.pdf').'"');
    }

    public function test_cv_is_listed_in_the_sitemap(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('<loc>'.route('en.cv').'</loc>', false)
            ->assertSee('<loc>'.route('es.cv').'</loc>', false)
            ->assertDontSee(route('en.cv.markdown'), false)
            ->assertDontSee(route('en.cv.pdf'), false);
    }
}
