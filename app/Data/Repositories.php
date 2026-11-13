<?php

namespace App\Data;

class Repositories
{
    private static array $projects = [
        [
            'repository' => 'laravel-magiclink',
            'owner' => 'cesargb',
            'tags' => ['Laravel', 'PHP'],
            'summary' => [
                'es' => 'Genera enlaces seguros que ejecutan acciones autenticadas en aplicaciones Laravel.',
                'en' => 'Generates secure links that run authenticated actions in Laravel applications.',
            ],
            'uses' => [
                'es' => [
                    'Inicio de sesión sin contraseña',
                    'Acceso a contenido privado',
                    'Acciones protegidas con enlaces temporales',
                ],
                'en' => [
                    'Passwordless login',
                    'Access to private content',
                    'Protected actions through temporary links',
                ],
            ],
        ],
        [
            'repository' => 'php-log-rotation',
            'owner' => 'cesargb',
            'tags' => ['PHP'],
            'summary' => [
                'es' => 'Rota, comprime y elimina los logs de aplicaciones PHP con una API ligera.',
                'en' => 'Rotates, compresses and prunes PHP application logs through a lightweight API.',
            ],
            'uses' => [
                'es' => [
                    'Rotación automática de ficheros de log',
                    'Borrado de logs antiguos',
                    'Compresión para ahorrar espacio',
                ],
                'en' => [
                    'Automatic log file rotation',
                    'Pruning of old logs',
                    'Compression to save storage',
                ],
            ],
        ],
    ];

    public static function all(): array
    {
        return self::$projects;
    }
}
