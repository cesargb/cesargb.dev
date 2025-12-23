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
                'es' => "`MagicLink` es un paquete para `Laravel` que te permite generar una url segura que al visitarla realiza tareas que requieren una validación, estos son algunos ejemplos:\n- Login sin contraseña; podrás enviar un enlace seguro con un sistema de autologin\n- Descarga/Acceso de contenido privado\n- Tareas customizada según tus necesidades",
                'en' => "`MagicLink` is a package for `Laravel` that allows you to generate a secure URL that, when visited, performs tasks that require validation. Some examples are:\n- Passwordless login; you can send a secure link with an autologin system\n- Download / access of private content\n- Customized tasks according to your needs",
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
                'es' => "Este paquete desarrollado en `PHP` te permite agregar en tus proyectos una API sencilla para rotar los logs de tus aplicaciones. Estas son sus principales funcionalidades:\n- Eliminación de ficheros rotados antiguos\n- Compresion para lograr un ahorro de espacio",
                'en' => "This package, developed in PHP, allows you to add a simple API to rotate the logs of your applications in your projects. These are its main functionalities:\n- Deletion of old rotated files\n- Compression to save space",
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
