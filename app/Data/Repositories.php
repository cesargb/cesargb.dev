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
            'badges' => [
                [
                    'image' => 'https://img.shields.io/packagist/v/cesargb/laravel-magiclink?color=%23e25151&label=version&style=for-the-badge&cacheSeconds=86400',
                    'url' => 'https://packagist.org/packages/cesargb/laravel-magiclink',
                    'alt' => 'Version paquete cesargb laravel-magiclink',
                ],
                [
                    'image' => 'https://img.shields.io/packagist/dt/cesargb/laravel-magiclink?color=%23e25151&label=descargas&style=for-the-badge&cacheSeconds=3600',
                    'url' => 'https://packagist.org/packages/cesargb/laravel-magiclink',
                    'alt' => 'Descargas paquete cesargb laravel-magiclink',
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
            'badges' => [
                [
                    'image' => 'https://img.shields.io/packagist/v/cesargb/php-log-rotation?color=%23e25151&label=version&style=for-the-badge&cacheSeconds=86400',
                    'url' => 'https://packagist.org/packages/cesargb/php-log-rotation',
                    'alt' => 'Version paquete cesargb php log rotaion',
                ],
                [
                    'image' => 'https://img.shields.io/packagist/dt/cesargb/php-log-rotation?color=%23e25151&label=descargas&style=for-the-badge&cacheSeconds=3600',
                    'url' => 'https://packagist.org/packages/cesargb/php-log-rotation',
                    'alt' => 'Descargas paquete cesargb php log rotaion',
                ],
            ],
        ],
    ];

    public static function all(): array
    {
        return self::$projects;
    }
}
