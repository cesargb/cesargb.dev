<?php

namespace App\Http\Controllers;

use App\Data\Repositories;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
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

        return view('open-source.show', [
            'repository' => $project,
            'title' => $this->title($markdown) ?? $project['repository'],
            'content' => Str::markdown($this->withoutTitle($markdown), [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ], [
                new HighlightExtension,
            ]),
        ]);
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
