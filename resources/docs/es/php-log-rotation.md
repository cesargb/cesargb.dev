# PHP Log Rotation

PHP Log Rotation rota, comprime y elimina los ficheros de log de tu aplicación con una API
pequeña y fluida, sin herramientas externas como `logrotate`.

## Instalación

```bash
composer require cesargb/php-log-rotation
```

## Primeros pasos

```php
use Cesargb\Log\Rotation;

$rotation = new Rotation();

$rotation
    ->compress()      // comprime con gzip los ficheros rotados
    ->files(30)       // conserva los últimos 30 ficheros
    ->minSize(1024)   // solo rota ficheros de más de 1 KB
    ->rotate('storage/logs/app.log');
```

## Más información

La referencia completa está en el [repositorio de GitHub](https://github.com/cesargb/php-log-rotation).
