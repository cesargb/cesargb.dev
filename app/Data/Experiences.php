<?php

namespace App\Data;

class Experiences
{
    /**
     * Areas of the stack shown as cards on the home page. Translatable copy lives in
     * `lang/{locale}/main.php` under `stack_{key}_*`. Items with a `mark` render a short
     * monospace label; `highlight` marks the core technologies of the area. Items with a
     * `label` key are translated instead of shown verbatim.
     * `more` is an optional disclosure: extra chips or a single line of text.
     */
    private static array $experiences = [
        [
            'key' => 'backend',
            'featured' => true,
            'items' => [
                ['name' => 'Laravel', 'mark' => 'Lr', 'highlight' => true],
                ['name' => 'PHP', 'mark' => '<?>', 'highlight' => true],
                ['name' => 'MySQL'],
                ['name' => 'Redis'],
            ],
            'more' => ['type' => 'chips', 'items' => ['Node.js', 'PostgreSQL']],
        ],
        [
            'key' => 'frontend',
            'items' => [
                ['name' => 'Vue.js', 'mark' => 'V', 'highlight' => true],
                ['name' => 'JavaScript', 'mark' => 'JS'],
                ['name' => 'CSS / Sass'],
            ],
        ],
        [
            'key' => 'infrastructure',
            'items' => [
                ['name' => 'Linux'],
                ['name' => 'Docker'],
                ['name' => 'AWS'],
                ['name' => 'GitHub Actions'],
            ],
        ],
        [
            'key' => 'services',
            'items' => [
                ['name' => 'Hosting'],
                ['name' => 'DNS'],
                ['label' => 'stack_email'],
            ],
            'more' => ['type' => 'text', 'items' => ['HTTP', 'FTP', 'SMTP', 'POP / IMAP']],
        ],
    ];

    public static function all(): array
    {
        return self::$experiences;
    }
}
