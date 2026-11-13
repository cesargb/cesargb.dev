<?php

namespace App\Data;

class Experiences
{
    /**
     * Layers of the stack, from the top (closest to the user) to the bottom.
     * Each item's `mark` is the short monospace label shown in its chip.
     */
    private static array $experiences = [
        [
            'title' => 'experience_title_frontend',
            'layer' => 'L3',
            'items' => [
                ['name' => 'CSS/SASS', 'mark' => 'CSS', 'level' => 'expert'],
                ['name' => 'JavaScript', 'mark' => 'JS', 'level' => 'expert'],
                ['name' => 'Vue.js', 'mark' => 'Vue', 'level' => 'expert'],
            ],
        ],
        [
            'title' => 'experience_title_backend',
            'layer' => 'L2',
            'items' => [
                ['name' => 'PHP', 'mark' => 'PHP', 'level' => 'expert'],
                ['name' => 'Laravel', 'mark' => 'Lv', 'level' => 'expert'],
                ['name' => 'Node.js', 'mark' => 'Nd', 'level' => 'advance'],
                ['name' => 'MySQL', 'mark' => 'My', 'level' => 'expert'],
                ['name' => 'PostgreSQL', 'mark' => 'Pg', 'level' => 'advance'],
                ['name' => 'Redis', 'mark' => 'Rd', 'level' => 'expert'],
            ],
        ],
        [
            'title' => 'experience_title_cloud',
            'layer' => 'L1',
            'items' => [
                ['name' => 'Linux', 'mark' => 'Lx', 'level' => 'expert'],
                ['name' => 'AWS', 'mark' => 'AWS', 'level' => 'advance'],
                ['name' => 'Docker', 'mark' => 'Dk', 'level' => 'advance'],
                ['name' => 'GitHub Actions', 'mark' => 'CI', 'level' => 'advance'],
            ],
        ],
    ];

    public static function all(): array
    {
        return self::$experiences;
    }
}
