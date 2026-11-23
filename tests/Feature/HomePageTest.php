<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Cesargb;
use App\Data\Career;
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

    public function test_header_has_mobile_menu_with_cv_link(): void
    {
        foreach (['en' => 'Menu', 'es' => 'Menú'] as $lang => $label) {
            $this->get("/{$lang}")
                ->assertOk()
                ->assertSeeHtml('popovertarget="site-menu"')
                ->assertSeeHtml('<div id="site-menu" class="bp-menu" popover>')
                ->assertSeeHtml('<span>@cesargb</span>')
                ->assertSeeHtml("aria-label=\"{$label}\"")
                ->assertSeeHtml('href="'.route("{$lang}.cv").'"');
        }
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
            ->assertStatus(302)
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

    public function test_home_exposes_complete_open_graph(): void
    {
        $image = e(Cesargb::PROFILE_IMAGE);

        foreach (['en' => ['en_US', 'es_ES'], 'es' => ['es_ES', 'en_US']] as $lang => [$locale, $alternate]) {
            $this->get("/{$lang}")
                ->assertOk()
                ->assertSeeHtml('<meta property="og:site_name" content="César García">')
                ->assertSeeHtml("<meta property=\"og:locale\" content=\"{$locale}\">")
                ->assertSeeHtml("<meta property=\"og:locale:alternate\" content=\"{$alternate}\">")
                ->assertSeeHtml("<meta property=\"og:image\" content=\"{$image}\">")
                ->assertSeeHtml('<meta property="og:image:alt" content="César García">')
                ->assertSeeHtml('<meta name="twitter:card" content="summary">');
        }
    }

    public function test_home_exposes_person_and_profile_page_json_ld(): void
    {
        foreach (['en', 'es'] as $lang) {
            preg_match_all(
                '#<script type="application/ld\+json">(.*?)</script>#s',
                $this->get("/{$lang}")->assertOk()->getContent(),
                $matches
            );

            $schemas = collect($matches[1])->map(fn ($json) => json_decode($json, true, flags: JSON_THROW_ON_ERROR))->keyBy('@type');

            $this->assertSame(Cesargb::PERSON_ID, $schemas['Person']['@id']);
            $this->assertSame(Cesargb::PROFILE_IMAGE, $schemas['Person']['image']);
            $this->assertSame(route("{$lang}.index"), $schemas['ProfilePage']['url']);
            $this->assertSame($lang, $schemas['ProfilePage']['inLanguage']);
            $this->assertSame(['@id' => Cesargb::PERSON_ID], $schemas['ProfilePage']['mainEntity']);
        }
    }

    public function test_open_graph_image_is_served_as_jpeg(): void
    {
        $path = parse_url(Cesargb::PROFILE_IMAGE, PHP_URL_PATH).'?'.parse_url(Cesargb::PROFILE_IMAGE, PHP_URL_QUERY);

        $this->get($path)
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_home_hero_shows_years_of_experience_and_focus(): void
    {
        $years = date('Y') - Career::startYear();

        $this->get('/es')
            ->assertOk()
            ->assertSeeHtml("<span class=\"bp-xp__number\">{$years}</span>")
            ->assertSee('de experiencia')
            ->assertSeeInOrder(['Sistemas', 'Redes', 'Desarrollo', 'Arquitectura de software', 'DevOps']);

        $this->get('/en')
            ->assertOk()
            ->assertSeeHtml("<span class=\"bp-xp__number\">{$years}</span>")
            ->assertSee('of experience')
            ->assertSeeInOrder(['Systems', 'Networks', 'Development', 'Software architecture', 'DevOps']);
    }

    public function test_stack_shows_area_cards_with_disclosures(): void
    {
        $this->get('/es')
            ->assertOk()
            ->assertSee('Del servidor a la interfaz.')
            ->assertSeeInOrder(['La lógica detrás.', 'Lo que se ve. Y se siente.', 'Del código a producción.', 'Todo conectado.'])
            ->assertSeeInOrder(['También trabajo con', 'Node.js', 'PostgreSQL'])
            ->assertSeeInOrder(['Protocolos y servicios', 'HTTP · FTP · SMTP · POP / IMAP'])
            ->assertSeeHtml('<details class="bp-stack-card__more">');

        $this->get('/en')
            ->assertOk()
            ->assertSee('From server to interface.')
            ->assertSeeInOrder(['The logic behind.', 'What you see. And feel.', 'From code to production.', 'Everything connected.'])
            ->assertSee('Email');
    }

    public function test_home_hero_intro_and_meta_description(): void
    {
        foreach (['es', 'en'] as $lang) {
            app()->setLocale($lang);
            $description = __('page-index.description');

            $this->assertGreaterThanOrEqual(120, mb_strlen($description));
            $this->assertLessThanOrEqual(160, mb_strlen($description));

            $this->get("/{$lang}")
                ->assertOk()
                ->assertSee(__('page-index.profile_content'))
                ->assertSee(__('page-index.profile_content_detail'))
                ->assertSeeHtml('<meta name="description" content="'.e($description).'" />');
        }
    }

    public function test_home_lists_professional_trajectory(): void
    {
        foreach (['en', 'es'] as $lang) {
            $response = $this->get("/{$lang}")
                ->assertOk()
                ->assertSee('id="trajectory"', false)
                ->assertSee("href=\"/{$lang}#trajectory\"", false);

            foreach (Career::all() as $job) {
                $response
                    ->assertSee($job['company'])
                    ->assertSee($job['role'][$lang]);
            }
        }
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
