<?php

namespace App\Data;

class Repositories
{
    private static array $projects = [
        [
            'repository' => 'laravel-magiclink',
            'owner' => 'cesargb',
            'tags' => ['Laravel', 'PHP'],
            'description' => [
                'es' => "`MagicLink` es un paquete para Laravel que permite generar enlaces seguros que ejecutan acciones que requieren autenticación o validación.\n\nCasos de uso habituales:\n\n- Inicio de sesión sin contraseña mediante enlaces seguros (passwordless login)\n- Acceso a contenido privado\n- Ejecución de acciones protegidas mediante enlaces temporales\n\nEste paquete facilita la implementación de flujos seguros y simplifica la experiencia de usuario en aplicaciones Laravel.",
                'en' => "`MagicLink` is a package for Laravel that allows you to generate secure links that execute actions requiring authentication or validation.\n\nCommon use cases:\n\n- Passwordless login through secure links\n- Access to private content\n- Execution of protected actions through temporary links\n\nThis package makes it easy to implement secure flows and simplifies the user experience in Laravel applications.",
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
            'description' => [
                'es' => "Paquete en PHP diseñado para `gestionar la rotación de logs de aplicaciones` de forma sencilla mediante una API ligera.\n\nFuncionalidades principales:\n\n- Rotación automática de archivos de log\n- Eliminación de logs antiguos\n- Compresión de archivos para optimizar el almacenamiento\n\nEste paquete permite integrar fácilmente la gestión de logs en cualquier aplicación PHP.",
                'en' => "PHP package designed to `manage application log rotation` easily through a lightweight API.\n\nMain features:\n\n- Automatic log file rotation\n- Deletion of old logs\n- File compression to optimize storage\n\nThis package allows you to easily integrate log management into any PHP application.",
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
