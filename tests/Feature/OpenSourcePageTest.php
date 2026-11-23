<?php

namespace Tests\Feature;

use App\Data\Repositories;
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

    public function test_project_cards_show_stored_stars_and_downloads(): void
    {
        $storage = sys_get_temp_dir().'/open-source-stats-'.uniqid();
        mkdir($storage.'/app', 0777, true);
        file_put_contents($storage.'/app/repositories_stats.php', '<?php return '.var_export([
            'cesargb/laravel-magiclink' => ['github_starts' => 470, 'downloads' => 1876487],
            'cesargb/php-log-rotation' => ['github_starts' => 19, 'downloads' => 398567],
        ], true).';');
        $this->app->useStoragePath($storage);

        try {
            $this->get('/en/open-source')
                ->assertOk()
                ->assertSee('GitHub stars')
                ->assertSeeHtml('<dd>470</dd>')
                ->assertSeeHtml('<dd>1.9M</dd>')
                ->assertSeeHtml('title="1,876,487"')
                ->assertSeeHtml('<dd>399k</dd>');

            $this->get('/es')
                ->assertOk()
                ->assertSee('Estrellas en GitHub')
                ->assertSeeHtml('<dd>1.9M</dd>');

            $this->get('/en/open-source/laravel-magiclink')
                ->assertOk()
                ->assertSeeHtml('<dd>470</dd>')
                ->assertSeeHtml('<dd>1.9M</dd>');
        } finally {
            unlink($storage.'/app/repositories_stats.php');
            rmdir($storage.'/app');
            rmdir($storage);
        }
    }

    public function test_project_cards_render_without_stats(): void
    {
        $this->app->useStoragePath(sys_get_temp_dir().'/open-source-stats-missing-'.uniqid());

        $this->get('/en/open-source')
            ->assertOk()
            ->assertDontSee('bp-stats', false);
    }

    public function test_home_only_features_the_two_main_projects(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('cesargb/<b>laravel-magiclink</b>', false)
            ->assertSee('cesargb/<b>php-log-rotation</b>', false)
            ->assertDontSee('cesargb/<b>laravel-logs-rotate</b>', false);
    }

    public function test_index_cards_use_h2_right_under_the_h1_while_home_keeps_h3(): void
    {
        $this->get('/en/open-source')
            ->assertOk()
            ->assertSeeHtml('<h2 class="bp-card__name">cesargb/<b>laravel-magiclink</b></h2>')
            ->assertDontSeeHtml('<h3 class="bp-card__name">');

        $this->get('/en')->assertSeeHtml('<h3 class="bp-card__name">cesargb/<b>laravel-magiclink</b></h3>');
    }

    public function test_project_page_github_link_announces_new_tab_and_back_arrow_is_hidden(): void
    {
        $html = $this->get('/en/open-source/laravel-magiclink')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('#target="_blank"\s+rel="noopener"\s*>.*?View on GitHub\s*<span class="sr-only">\(opens in a new tab\)</span>\s*</a>#s', $html);
        $this->assertStringContainsString('<span aria-hidden="true">←</span> All projects', $html);
    }

    public function test_project_page_renders_markdown_documentation(): void
    {
        $this->get('/en/open-source/laravel-magiclink')
            ->assertOk()
            ->assertSeeHtml('<h1 class="doc-title">Laravel MagicLink</h1>')
            ->assertSeeHtml('<h2 id="installation">Installation<a href="#installation" class="heading-anchor"')
            ->assertSee('<title>Laravel MagicLink · Documentation · César García</title>', false);

        $this->get('/es/open-source/php-log-rotation')
            ->assertOk()
            ->assertSeeHtml('<h1 class="doc-title">PHP Log Rotation</h1>')
            ->assertSeeHtml('<h2 id="instalación">Instalación');
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
            ->assertSeeHtml('<h2 id="configuración">Configuración');
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
            ->assertSeeHtml('<h2 id="borrar-datos-residuales">Borrar datos residuales');
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

    public function test_long_documents_get_a_table_of_contents(): void
    {
        $this->get('/en/open-source/laravel-magiclink')
            ->assertOk()
            ->assertSeeHtml('<details class="doc-toc" open data-doc-toc>')
            ->assertSeeHtml('<summary class="doc-toc__title" id="doc-toc-title">On this page</summary>')
            ->assertSee('On this page')
            ->assertSeeHtml('<a href="#login-action">Login Action</a>')
            ->assertSeeHtml('<a href="#protect-with-an-access-code">Protect with an access code</a>')
            ->assertSeeHtml('<h3 id="login-action">');

        $this->get('/es/open-source/laravel-magiclink')
            ->assertOk()
            ->assertSee('En esta página')
            ->assertSeeHtml('<a href="#iniciar-sesión">Iniciar sesión</a>');
    }

    public function test_short_documents_have_no_table_of_contents(): void
    {
        $this->get('/en/open-source/php-log-rotation')
            ->assertOk()
            ->assertDontSeeHtml('<details class="doc-toc"');
    }

    public function test_every_in_page_link_points_to_an_existing_heading(): void
    {
        foreach (['en', 'es'] as $locale) {
            foreach (Repositories::slugs() as $repository) {
                $html = $this->get("/{$locale}/open-source/{$repository}")->assertOk()->getContent();

                preg_match_all('/\bid="([^"]+)"/', $html, $ids);
                preg_match_all('/href="#([^"]+)"/', $html, $anchors);

                $missing = array_diff(
                    array_map(fn ($anchor) => html_entity_decode(rawurldecode($anchor)), $anchors[1]),
                    array_map('html_entity_decode', $ids[1]),
                );

                $this->assertSame([], array_values(array_unique($missing)), "Broken anchors in {$locale}/{$repository}");
            }
        }
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
