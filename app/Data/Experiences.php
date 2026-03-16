<?php

namespace App\Data;

class Experiences
{
    private static array $experiences = [
        [
            'title' => 'experience_title_backend',
            'items' => [
                ['name' => 'PHP', 'level' => 'expert'],
                ['name' => 'Laravel', 'level' => 'expert'],
                ['name' => 'Node.js', 'level' => 'advance'],
            ],
        ],
        [
            'title' => 'experience_title_frontend',
            'items' => [
                ['name' => 'HTML/CSS', 'level' => 'expert'],
                ['name' => 'JavaScript', 'level' => 'advance'],
                ['name' => 'Vue.js', 'level' => 'expert'],
            ],
        ],
        [
            'title' => 'experience_title_cloud',
            'items' => [
                ['name' => 'AWS', 'level' => 'advance'],
                ['name' => 'Linux', 'level' => 'expert'],
                ['name' => 'Docker', 'level' => 'advance'],
            ],
        ],
    ];

    public static function all(): array
    {
        return self::$experiences;
    }
}
