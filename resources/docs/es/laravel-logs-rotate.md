# Laravel Logs Rotate

Rota y comprime los ficheros de log de Laravel. Este paquete es útil cuando usas el canal de log
`single` (`StreamHandler`).

## Instalación

Instala el paquete con Composer:

```bash
composer require cesargb/laravel-logs-rotate
```

## Uso

Una vez instalado, tu aplicación ejecuta cada día a las 00:00 una tarea programada que rota los
ficheros de log de Laravel.

## Configuración

Si necesitas cambiar la frecuencia o cualquier otra opción, publica el fichero de configuración:

```bash
php artisan vendor:publish --provider="Cesargb\LaravelLog\RotateServiceProvider" --tag=config
```

Este es el contenido del fichero `config/rotate.php` publicado:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Compression Enable
    |--------------------------------------------------------------------------
    |
    | This option defines if the file rotated must be compressed.
    | If you prefer not compress file, set this value at false.
    */
    'log_compress_files' => true,

    /*
    |--------------------------------------------------------------------------
    | Schedule Rotate
    |--------------------------------------------------------------------------
    |
    | Determine when must be run the cron.
    | You can disable the schedule change the option enable at false.
    | You can change the frequency with option cron.
    |
    */
    'schedule' => [
        'enable'    => true,
        'cron'      => '0 0 * * *',
    ],

    /*
    |--------------------------------------------------------------------------
    | Max Files Rotated
    |--------------------------------------------------------------------------
    |
    | This value determine the max number of files rotated in the archive folder.
    |
    */
    'log_max_files' => env('LOG_MAX_FILES', 30),

   /*
    |--------------------------------------------------------------------------
    | Minimum Log File Size
    |--------------------------------------------------------------------------
    |
    | This value determines the minimum size (in bytes) that a log file must
    | have before it can be rotated. Files smaller than this size will not
    | be rotated. Set to 0 to disable this check and rotate regardless of size.
    |
    */
    'log_min_size' => env('LOG_MIN_SIZE', 0),

    /*
    |--------------------------------------------------------------------------
    | Truncate Log file
    |--------------------------------------------------------------------------
    |
    | This option defines if the log file must be truncated after rotated.
    | If you prefer not truncate file, set this value at false.
    */
    'truncate' => env('LOG_TRUNCATE', true),

    /*
    |--------------------------------------------------------------------------
    | Other files to rotated
    |--------------------------------------------------------------------------
    |
    | Array the other foreign files
    |
    | Example:
    |   'foreign_files' => [
            storage_path('/logs/worker.log')
    |   ]
    |
    */
    'foreign_files' => []
];
```

### Consejo de rendimiento para Monolog 3.10+

Si usas `monolog/monolog:^3.10` o superior, puedes desactivar la opción `truncate` para que la
rotación sea más eficiente:

```php
'truncate' => false,
```

Con Monolog 3.10+ el proceso de rotación está optimizado y ya no hace falta truncar el fichero, lo
que mejora el rendimiento. Más detalles en el
[PR #1963 de monolog](https://github.com/Seldaek/monolog/pull/1963).

## Comando

Usa el comando `rotate:files` para rotar otros ficheros:

```bash
php artisan rotate:files --help
Description:
  Rotate files

Usage:
  rotate:files [options]

Options:
  -f, --file[=FILE]            Files to rotate (multiple values allowed)
  -c, --compress[=COMPRESS]    Compress the file rotated [default: "true"]
  -m, --max-files[=MAX-FILES]  Max files rotated [default: "5"]
  -d, --dir[=DIR]              Dir where archive the file rotated
```

## Eventos

Cada vez que se rota un fichero se lanza uno de estos eventos:

### RotateWasSuccessful

`Cesargb\LaravelLog\Events\RotateWasSuccessful`

Se lanza cuando la rotación termina correctamente. Tiene dos propiedades públicas:

* `filename`: la ruta completa del fichero que se rota.
* `filenameTarget`: la ruta completa del fichero rotado.

### RotateHasFailed

`Cesargb\LaravelLog\Events\RotateHasFailed`

Se lanza cuando se produce un error durante la rotación. Tiene dos propiedades públicas:

* `filename`: la ruta completa del fichero que se rota.
* `exception`: un objeto que extiende la clase `Exception` de PHP.

## Limitaciones

Solo se pueden rotar los ficheros de log escritos por el canal `StreamHandler`.

## Actualizar de versión

Consulta [UPGRADING](https://github.com/cesargb/laravel-logs-rotate/blob/master/UPGRADING.md) para ver los detalles.

## Contribuir y licencia

Cualquier contribución es bienvenida. El paquete se publica bajo licencia MIT, consulta el
[fichero de licencia](https://github.com/cesargb/laravel-logs-rotate/blob/master/LICENSE.md).

## Más información

El código fuente está en el [repositorio de GitHub](https://github.com/cesargb/laravel-logs-rotate).
