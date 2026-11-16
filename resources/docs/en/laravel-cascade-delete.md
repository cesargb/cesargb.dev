# Laravel Cascade Delete

Cascade deletion for polymorphic relations in Laravel apps. The package provides a trait for your
Eloquent models that deletes their polymorphic relations (`MorphOne`, `MorphMany` or
`MorphToMany`) in cascade.

## Installation

Requires PHP 8.2+ and Laravel 11 or higher. Install the package via Composer:

```bash
composer require cesargb/laravel-cascade-delete
```

## Usage

Add the `Cesargb\Database\Support\CascadeDelete` trait to your Eloquent model and define the
`$cascadeDeleteMorph` property with a string or an array of the methods that define its
polymorphic relations.

```php
<?php

namespace App\Models;

use Cesargb\Database\Support\CascadeDelete;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use CascadeDelete;

    protected $cascadeDeleteMorph = ['image', 'tags', 'options'];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function options()
    {
        return $this->morphMany(Option::class, 'optionable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
```

Now, when you delete a `Video`, its associated `Image`, its `Option` records and the `Tag` pivot
records are deleted too.

## Deleting residual data

Bulk deletes (`Model::query()->delete()`) don't fire model events, so they leave residual
polymorphic data behind. Clean it up with the `deleteMorphResidual` method:

```php
Video::query()->delete();

(new Video)->deleteMorphResidual();
```

### Command to remove all residual data

The `morph:clean` Artisan command removes the residual data of every model that uses the
`CascadeDelete` trait:

```bash
php artisan morph:clean
```

Add `--dry-run` to see how many rows would be removed without deleting anything.

## Contributing

Any contributions are welcome.

## More information

The source code lives in the [GitHub repository](https://github.com/cesargb/laravel-cascade-delete).
