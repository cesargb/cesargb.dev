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
        [
            'repository' => 'laravel-logs-rotate',
            'owner' => 'cesargb',
            'tags' => ['Laravel', 'PHP'],
            'summary' => [
                'es' => 'Rota y comprime los ficheros de log de Laravel de forma programada.',
                'en' => 'Rotates and compresses Laravel log files on a schedule.',
            ],
            'uses' => [
                'es' => [
                    'Rotación diaria automática del log de Laravel',
                    'Compresión y límite de ficheros archivados',
                    'Rotación de otros ficheros con un comando Artisan',
                ],
                'en' => [
                    'Automatic daily rotation of the Laravel log',
                    'Compression and a cap on archived files',
                    'Rotation of other files through an Artisan command',
                ],
            ],
        ],
        [
            'repository' => 'laravel-cascade-delete',
            'owner' => 'cesargb',
            'tags' => ['Laravel', 'PHP', 'Eloquent'],
            'summary' => [
                'es' => 'Borrado en cascada de las relaciones polimórficas en los modelos Eloquent de Laravel.',
                'en' => 'Cascade deletion of polymorphic relations in Laravel Eloquent models.',
            ],
            'uses' => [
                'es' => [
                    'Borrado de relaciones MorphOne, MorphMany y MorphToMany',
                    'Limpieza de datos huérfanos tras borrados masivos',
                    'Comando Artisan para limpiar todos los modelos',
                ],
                'en' => [
                    'Deletion of MorphOne, MorphMany and MorphToMany relations',
                    'Cleanup of orphaned data after bulk deletes',
                    'Artisan command to clean every model',
                ],
            ],
        ],
    ];

    public static function all(): array
    {
        return self::$projects;
    }

    /**
     * The main projects, highlighted on the home page: the first ones in the list.
     */
    public static function featured(int $limit = 2): array
    {
        return array_slice(self::$projects, 0, $limit);
    }

    /**
     * @return array<int, string>
     */
    public static function slugs(): array
    {
        return array_column(self::$projects, 'repository');
    }

    public static function find(string $repository): ?array
    {
        foreach (self::$projects as $project) {
            if ($project['repository'] === $repository) {
                return $project;
            }
        }

        return null;
    }
}
