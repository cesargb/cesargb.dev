<?php

namespace App\Http\Controllers;

use App\Data\Repositories;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use Tempest\Highlight\CommonMark\HighlightExtension;

class OpenSourceController extends Controller
{
    public function index(): View
    {
        return view('open-source.index', [
            'repositories' => Repositories::all(),
        ]);
    }

    public function show(string $repository): View
    {
        $project = Repositories::find($repository) ?? abort(404);

        $path = resource_path('docs/'.app()->getLocale().'/'.$project['repository'].'.md');

        abort_unless(File::exists($path), 404);

        $markdown = File::get($path);

        $content = Str::markdown($this->withoutTitle($markdown), [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'heading_permalink' => [
                'apply_id_to_heading' => true,
                'id_prefix' => '',
                'fragment_prefix' => '',
                'symbol' => '#',
                'insert' => 'after',
                'min_heading_level' => 2,
                'max_heading_level' => 4,
                'html_class' => 'heading-anchor',
                'aria_hidden' => true,
            ],
        ], [
            new HighlightExtension,
            new HeadingPermalinkExtension,
        ]);

        return view('open-source.show', [
            'repository' => $project,
            'title' => $this->title($markdown) ?? $project['repository'],
            'content' => $content,
            'toc' => $this->tableOfContents($content),
        ]);
    }

    /**
     * The h2 and h3 headings of the rendered document, used to build the page index.
     *
     * @return array<int, array{level: int, id: string, text: string}>
     */
    private function tableOfContents(string $html): array
    {
        preg_match_all('/<h([23]) id="([^"]+)">(.*?)<\/h\1>/s', $html, $matches, PREG_SET_ORDER);

        return array_map(fn (array $match) => [
            'level' => (int) $match[1],
            'id' => html_entity_decode($match[2], ENT_QUOTES | ENT_HTML5),
            'text' => trim(html_entity_decode(strip_tags(
                preg_replace('/<a[^>]*class="heading-anchor"[^>]*>.*?<\/a>/s', '', $match[3]) ?? $match[3]
            ), ENT_QUOTES | ENT_HTML5)),
        ], $matches);
    }

    /**
     * The first level-one heading of the document, used as the page title.
     */
    private function title(string $markdown): ?string
    {
        return preg_match('/^#\s+(.+)$/m', $markdown, $matches) ? trim($matches[1]) : null;
    }

    /**
     * The document without its first level-one heading, which the view renders as the page <h1>.
     */
    private function withoutTitle(string $markdown): string
    {
        return preg_replace('/^#\s+.+$\R?/m', '', $markdown, 1) ?? $markdown;
    }
}
