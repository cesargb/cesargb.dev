# Laravel MagicLink

Laravel MagicLink genera enlaces seguros y temporales que ejecutan una acción al abrirse:
iniciar sesión, mostrar un fichero privado, renderizar una vista o ejecutar tu propio código.

## Instalación

```bash
composer require cesargb/laravel-magiclink
```

Publica y ejecuta la migración que crea la tabla de enlaces:

```bash
php artisan vendor:publish --provider="MagicLink\MagicLinkServiceProvider" --tag=migrations
php artisan migrate
```

## Primeros pasos

Crea un enlace que inicia sesión sin contraseña:

```php
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;

$action = new LoginAction(User::first());

$url = MagicLink::create($action)->url;
```

Envía `$url` al usuario (por email, por ejemplo). Al abrirlo, inicia sesión.

## Más información

La referencia completa está en el [repositorio de GitHub](https://github.com/cesargb/laravel-magiclink).
