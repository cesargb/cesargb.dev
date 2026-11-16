<?php

namespace Tests\Feature;

use Tests\TestCase;

class OpenSourcePageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_index_lists_every_project_linking_to_its_documentation(): void
    {
        foreach (['en', 'es'] as $locale) {
            $this->get("/{$locale}/open-source")
                ->assertOk()
                ->assertSee(route("{$locale}.open-source.show", 'laravel-magiclink'), false)
                ->assertSee(route("{$locale}.open-source.show", 'php-log-rotation'), false)
                ->assertSee(route("{$locale}.open-source.show", 'laravel-logs-rotate'), false)
                ->assertSee(route("{$locale}.open-source.show", 'laravel-cascade-delete'), false);
        }
    }

    public function test_home_only_features_the_two_main_projects(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('cesargb/<b>laravel-magiclink</b>', false)
            ->assertSee('cesargb/<b>php-log-rotation</b>', false)
            ->assertDontSee('cesargb/<b>laravel-logs-rotate</b>', false);
    }

    public function test_project_page_renders_markdown_documentation(): void
    {
        $this->get('/en/open-source/laravel-magiclink')
            ->assertOk()
            ->assertSeeHtml('<h1 class="doc-title">Laravel MagicLink</h1>')
            ->assertSeeHtml('<h2>Installation</h2>')
            ->assertSee('<title>Laravel MagicLink · Documentation · César García</title>', false);

        $this->get('/es/open-source/php-log-rotation')
            ->assertOk()
            ->assertSeeHtml('<h1 class="doc-title">PHP Log Rotation</h1>')
            ->assertSeeHtml('<h2>Instalación</h2>');
    }

    public function test_laravel_logs_rotate_documentation_is_published(): void
    {
        $this->get('/en/open-source/laravel-logs-rotate')
            ->assertOk()
            ->assertSeeHtml('<h1 class="doc-title">Laravel Logs Rotate</h1>')
            ->assertSee('Cesargb\\LaravelLog\\Events\\RotateHasFailed')
            ->assertDontSee('RotativeHandler');

        $this->get('/es/open-source/laravel-logs-rotate')
            ->assertOk()
            ->assertSeeHtml('<h1 class="doc-title">Laravel Logs Rotate</h1>')
            ->assertSeeHtml('<h2>Configuración</h2>');
    }

    public function test_laravel_cascade_delete_documentation_is_published(): void
    {
        $this->get('/en/open-source/laravel-cascade-delete')
            ->assertOk()
            ->assertSeeHtml('<h1 class="doc-title">Laravel Cascade Delete</h1>')
            ->assertSee('morph:clean');

        $this->get('/es/open-source/laravel-cascade-delete')
            ->assertOk()
            ->assertSeeHtml('<h1 class="doc-title">Laravel Cascade Delete</h1>')
            ->assertSeeHtml('<h2>Borrar datos residuales</h2>');
    }

    public function test_code_blocks_are_syntax_highlighted(): void
    {
        $this->get('/en/open-source/laravel-magiclink')
            ->assertOk()
            ->assertSeeHtml('<pre data-lang="php" class="notranslate">')
            ->assertSeeHtml('<span class="hl-keyword">use</span>')
            ->assertSeeHtml('<span class="hl-variable">$action</span>')
            ->assertSeeHtml(')-&gt;<span class="hl-property">url</span>');

        $this->get('/es/open-source/php-log-rotation')
            ->assertOk()
            ->assertSeeHtml('<span class="hl-comment">');
    }

    public function test_code_blocks_get_a_localized_copy_button(): void
    {
        $this->get('/en/open-source/laravel-magiclink')
            ->assertOk()
            ->assertSee('copy: "Copy"', false)
            ->assertSee('copied: "Copied"', false);

        $this->get('/es/open-source/laravel-magiclink')
            ->assertOk()
            ->assertSee('copy: "Copiar"', false)
            ->assertSee('copied: "Copiado"', false);
    }

    public function test_unknown_project_returns_not_found(): void
    {
        $this->get('/en/open-source/unknown-project')->assertNotFound();
        $this->get('/es/open-source/unknown-project')->assertNotFound();
    }

    public function test_index_exposes_canonical_and_hreflang(): void
    {
        $en = route('en.open-source.index');
        $es = route('es.open-source.index');

        $this->get('/es/open-source')
            ->assertOk()
            ->assertSeeHtml("<link rel=\"canonical\" href=\"{$es}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"en\" href=\"{$en}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"es\" href=\"{$es}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"x-default\" href=\"{$en}\" />");
    }

    public function test_project_page_exposes_canonical_and_hreflang_with_parameters(): void
    {
        $en = route('en.open-source.show', 'laravel-magiclink');
        $es = route('es.open-source.show', 'laravel-magiclink');

        $this->get('/en/open-source/laravel-magiclink')
            ->assertOk()
            ->assertSeeHtml("<link rel=\"canonical\" href=\"{$en}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"en\" href=\"{$en}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"es\" href=\"{$es}\" />")
            ->assertSeeHtml("<link rel=\"alternate\" hreflang=\"x-default\" href=\"{$en}\" />")
            ->assertSeeHtml("<meta property=\"og:url\" content=\"{$en}\">");
    }

    public function test_pages_are_not_indexable_outside_production(): void
    {
        $this->get('/en/open-source')
            ->assertOk()
            ->assertSeeHtml('<meta name="robots" content="noindex,nofollow" />');

        $this->get('/es/open-source/laravel-magiclink')
            ->assertOk()
            ->assertSeeHtml('<meta name="robots" content="noindex,nofollow" />');
    }

    public function test_pages_are_indexable_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $this->get('/en/open-source')
            ->assertOk()
            ->assertDontSee('name="robots"', false);

        $this->get('/es/open-source/laravel-magiclink')
            ->assertOk()
            ->assertDontSee('name="robots"', false);
    }
}
