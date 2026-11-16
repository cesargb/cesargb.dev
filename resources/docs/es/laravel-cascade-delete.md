# Laravel Cascade Delete

Borrado en cascada de las relaciones polimórficas en aplicaciones Laravel. El paquete ofrece un trait
para tus modelos Eloquent que borra en cascada sus relaciones polimórficas (`MorphOne`,
`MorphMany` o `MorphToMany`).

## Instalación

Requiere PHP 8.2+ y Laravel 11 o superior. Instala el paquete con Composer:

```bash
composer require cesargb/laravel-cascade-delete
```

## Uso

Añade el trait `Cesargb\Database\Support\CascadeDelete` a tu modelo Eloquent y define la
propiedad `$cascadeDeleteMorph` con un string o un array con los métodos que definen sus
relaciones polimórficas.

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

Ahora, al borrar un `Video`, también se borran su `Image`, sus registros `Option` y los registros
de la tabla pivote de `Tag`.

## Borrar datos residuales

Los borrados masivos (`Model::query()->delete()`) no lanzan los eventos del modelo, así que dejan
datos polimórficos residuales. Puedes limpiarlos con el método `deleteMorphResidual`:

```php
Video::query()->delete();

(new Video)->deleteMorphResidual();
```

### Comando para borrar todos los datos residuales

El comando Artisan `morph:clean` borra los datos residuales de todos los modelos que usan el trait
`CascadeDelete`:

```bash
php artisan morph:clean
```

Añade `--dry-run` para ver cuántas filas se borrarían sin borrar nada.

## Contribuir

Cualquier contribución es bienvenida.

## Más información

El código fuente está en el [repositorio de GitHub](https://github.com/cesargb/laravel-cascade-delete).
