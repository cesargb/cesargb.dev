# Laravel Logs Rotate

Rotate and compress your Laravel log files. This package is useful when you use the `single` logger
channel (`StreamHandler`).

## Installation

Install the package via Composer:

```bash
composer require cesargb/laravel-logs-rotate
```

## Usage

Once installed, your application runs a scheduled task every day at 00:00 that rotates the Laravel
log files.

## Configuration

If you need to change the frequency or any other option, publish the config file:

```bash
php artisan vendor:publish --provider="Cesargb\LaravelLog\RotateServiceProvider" --tag=config
```

These are the contents of the published `config/rotate.php` file:

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

### Performance tip for Monolog 3.10+

If you are using `monolog/monolog:^3.10` or higher, you can disable the `truncate` option for a more
efficient rotation:

```php
'truncate' => false,
```

With Monolog 3.10+ the rotation process is optimized and truncating is no longer necessary, which
results in better performance. For more details, see
[monolog PR #1963](https://github.com/Seldaek/monolog/pull/1963).

## Command

Use the `rotate:files` command to rotate other files:

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

## Events

Every time a file is rotated, one of these events is fired:

### RotateWasSuccessful

`Cesargb\LaravelLog\Events\RotateWasSuccessful`

Fired when the rotation was successful. It has two public properties:

* `filename`: the full path of the file to rotate.
* `filenameTarget`: the full path of the rotated file.

### RotateHasFailed

`Cesargb\LaravelLog\Events\RotateHasFailed`

Fired when an error occurs during the rotation. It has two public properties:

* `filename`: the full path of the file to rotate.
* `exception`: an object that extends PHP's `Exception` class.

## About

Only log files written by the `StreamHandler` logger channel can be rotated.

## Upgrading

See [UPGRADING](https://github.com/cesargb/laravel-logs-rotate/blob/master/UPGRADING.md) for details.

## Contributing and license

Any contributions are welcome. The package is released under the MIT License, see the
[license file](https://github.com/cesargb/laravel-logs-rotate/blob/master/LICENSE.md).

## More information

The source code lives in the [GitHub repository](https://github.com/cesargb/laravel-logs-rotate).
