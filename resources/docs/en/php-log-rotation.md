# PHP Log Rotation

PHP Log Rotation rotates, compresses and prunes your application's log files through a small,
fluent API, with no external tools such as `logrotate`.

## Installation

```bash
composer require cesargb/php-log-rotation
```

## Quick start

```php
use Cesargb\Log\Rotation;

$rotation = new Rotation();

$rotation
    ->compress()      // gzip rotated files
    ->files(30)       // keep the last 30 files
    ->minSize(1024)   // only rotate files larger than 1 KB
    ->rotate('storage/logs/app.log');
```

## More information

The full reference lives in the [GitHub repository](https://github.com/cesargb/php-log-rotation).
