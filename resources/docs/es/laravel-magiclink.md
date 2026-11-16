# Laravel MagicLink

La clase `MagicLink` crea enlaces seguros que ejecutan una acción cuando se visitan. Sirven para
ofrecer contenido privado o incluso para iniciar sesión en tu aplicación.

> **¿Actualizando de versión?** Consulta el [CHANGELOG](https://github.com/cesargb/laravel-magiclink/blob/master/CHANGELOG.md)
> para ver qué ha cambiado y los pasos de migración necesarios.

## Instalación

Instala el paquete con Composer:

```bash
composer require cesargb/laravel-magiclink
```

### Preparar la base de datos

Publica la migración que crea la tabla `magic_links`:

```bash
php artisan vendor:publish --provider="MagicLink\MagicLinkServiceProvider" --tag="migrations"
```

Y ejecuta las migraciones:

```bash
php artisan migrate
```

## Caso de uso

Este ejemplo crea un enlace que inicia sesión en tu aplicación con el usuario indicado:

```php
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;

$urlToAutoLogin = MagicLink::create(new LoginAction($user))->url;
```

## Crear un MagicLink

El método `MagicLink::create` recibe la acción que se ejecutará y devuelve una instancia de
`MagicLink`. Su propiedad `url` es el enlace que envías a tu visitante.

## Acciones

Cada MagicLink tiene asociada una acción, que se ejecuta cuando se visita el enlace.

- [Iniciar sesión](#iniciar-sesión)
- [Descargar un fichero](#descargar-un-fichero)
- [Mostrar un fichero en el navegador](#mostrar-un-fichero-en-el-navegador)
- [Vista](#vista)
- [Respuesta HTTP](#respuesta-http)
- [Controlador](#controlador)
- [Acción personalizada](#acción-personalizada)
- [URL base personalizada](#url-base-personalizada)

### Iniciar sesión

La acción `LoginAction` inicia sesión en la aplicación a través del enlace generado.

Su constructor recibe el usuario que iniciará sesión. Opcionalmente, puedes indicar la
[respuesta HTTP](https://laravel.com/docs/responses) con el método `response`, o usar otro guard con
el método `guard`.

```php
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;

// Ejemplo 1: inicia sesión y redirige al dashboard
$action = new LoginAction(User::first());
$action->response(redirect('/dashboard'));

$urlToDashboard = MagicLink::create($action)->url;

// Ejemplo 2: inicia sesión y muestra el formulario para cambiar la contraseña, con el guard web
$action = new LoginAction(User::first());
$action->response(view('password.reset', ['email' => 'user@example.tld']));

$urlShowView = MagicLink::create($action)->url;

// Ejemplo 3: inicia sesión con otro guard y usa la redirección por defecto
$action = new LoginAction(User::first());
$action->guard('customguard');

$urlShowView = MagicLink::create($action)->url;

// Ejemplo 4: inicia sesión y recuerda al usuario
$action = new LoginAction(User::first());
$action->remember();

$urlShowView = MagicLink::create($action)->url;
```

### Descargar un fichero

La acción `DownloadFileAction` crea un enlace para descargar un fichero privado. Su constructor
recibe la ruta del fichero.

```php
use MagicLink\Actions\DownloadFileAction;
use MagicLink\MagicLink;

// URL para descargar el fichero storage_app('private_document.pdf')
$url = MagicLink::create(new DownloadFileAction('private_document.pdf'))->url;

// Descarga el fichero con otro nombre
$action = new DownloadFileAction('private_document.pdf', 'your_document.pdf');
$url = MagicLink::create($action)->url;

// Descarga el fichero desde otro disco
$action = (new DownloadFileAction('private_document.pdf'))->disk('ftp');
$url = MagicLink::create($action)->url;
```

### Mostrar un fichero en el navegador

La acción `InlineFileAction` crea un enlace que muestra un fichero privado directamente en el
navegador (un PDF o una imagen, por ejemplo) en lugar de forzar su descarga.

Acepta los mismos argumentos y métodos que `DownloadFileAction`: ruta del fichero, nombre del
fichero, `disk()` y `headers()`.

```php
use MagicLink\Actions\InlineFileAction;
use MagicLink\MagicLink;

// URL para mostrar el fichero storage_app('private_document.pdf') en el navegador
$url = MagicLink::create(new InlineFileAction('private_document.pdf'))->url;

// Muestra el fichero con otro nombre
$action = new InlineFileAction('private_document.pdf', 'your_document.pdf');
$url = MagicLink::create($action)->url;

// Muestra el fichero desde otro disco
$action = (new InlineFileAction('private_document.pdf'))->disk('ftp');
$url = MagicLink::create($action)->url;
```

### Vista

La acción `ViewAction` da acceso a una vista. Su constructor acepta los mismos argumentos que el
helper `view` de Laravel.

```php
use MagicLink\Actions\ViewAction;
use MagicLink\MagicLink;

// URL a la vista internal.blade.php
$url = MagicLink::create(new ViewAction('internal', [
    'data' => 'Your private custom content',
]))->url;
```

### Respuesta HTTP

La acción `ResponseAction` da acceso a contenido privado sin iniciar sesión. Su constructor recibe la
[respuesta HTTP](https://laravel.com/docs/responses) que devolverá la petición.

```php
use MagicLink\Actions\ResponseAction;
use MagicLink\MagicLink;

$action = new ResponseAction(function () {
    Auth::login(User::first());

    return redirect('/change_password');
});

$urlToCustomFunction = MagicLink::create($action)->url;
```

### Controlador

La acción `ControllerAction` llama directamente a un controlador.

Su constructor recibe el nombre de la clase del controlador. El segundo argumento, opcional, es el
método que se llamará; por defecto usa `__invoke`.

```php
use MagicLink\Actions\ControllerAction;
use MagicLink\MagicLink;

// Llama al método __invoke del controlador
$url = MagicLink::create(new ControllerAction(MyController::class))->url;

// Llama al método show del controlador
$url = MagicLink::create(new ControllerAction(MyController::class, 'show'))->url;
```

### Acción personalizada

Puedes crear tu propia acción extendiendo `MagicLink\Actions\ActionAbstract`:

```php
use MagicLink\Actions\ActionAbstract;

class MyCustomAction extends ActionAbstract
{
    public function __construct(public string $variable)
    {
    }

    public function run()
    {
        // Haz algo

        return response()->json([
            'success' => true,
            'data' => $this->variable,
        ]);
    }
}
```

Ya puedes crear un MagicLink con tu acción personalizada:

```php
use MagicLink\MagicLink;

$action = new MyCustomAction('Hello world');

$urlToCustomAction = MagicLink::create($action)->url;
```

#### Clases permitidas en acciones personalizadas

> **Buena práctica:** usa tipos primitivos (`int`, `string`, `array`…) como propiedades en lugar de
> objetos. Extrae en el constructor los datos que necesites de los objetos y guarda solo los valores
> primitivos.
>
> Por ejemplo, `LoginAction` recibe un objeto `User`, pero solo guarda su `authIdentifier`
> (int/string) en lugar del objeto completo. Es más seguro y no requiere configurar clases
> permitidas.

Cuando tu acción **tenga** que guardar objetos como propiedades (por ejemplo, modelos Eloquent),
debes permitir explícitamente que esas clases se deserialicen. Así se evitan vulnerabilidades por la
instanciación de objetos arbitrarios.

**Opción recomendada (tipos primitivos):**

```php
use App\Models\User;
use MagicLink\Actions\ActionAbstract;

class SendWelcomeEmailAction extends ActionAbstract
{
    public function __construct(public int $userId, public string $email)
    {
    }

    public function run()
    {
        $user = User::find($this->userId);
        // Envía el email a $user

        return response('Email sent!');
    }
}

// Uso
$action = new SendWelcomeEmailAction($user->id, $user->email);
MagicLink::create($action);
```

**Alternativa (objetos, requiere configuración):**

```php
use App\Models\User;
use MagicLink\Actions\ActionAbstract;

class SendWelcomeEmailAction extends ActionAbstract
{
    public function __construct(public User $user)
    {
    }

    public function run()
    {
        // Envía el email a $this->user

        return response('Email sent!');
    }
}
```

Para permitir que la clase `User` se deserialice, añádela en `config/magiclink.php`:

```php
'allowed_classes' => [
    \App\Models\User::class,
    \App\Models\Post::class,
    // Añade cualquier otra clase que usen tus acciones
],
```

O usa la variable de entorno `MAGICLINK_ALLOWED_CLASSES` con los nombres de clase separados por
comas:

```env
MAGICLINK_ALLOWED_CLASSES="App\Models\User,App\Models\Post"
```

> **Atención:** si usas objetos como propiedades en tus acciones personalizadas sin configurar las
> clases permitidas, obtendrás un `TypeError` al visitar el MagicLink. Añade siempre tus clases a la
> lista.

### URL base personalizada

Usa el método `baseUrl` para indicar la URL base de un magic link. Se asegura de que la URL termine
en barra, lista para generar el enlace.

```php
$magiclink = MagicLink::create($action);

$magiclink->baseUrl('http://example.com');

$urlShowView = $magiclink->url; // http://example.com/magiclink/...
```

## Proteger con un código de acceso

Opcionalmente, puedes proteger el recurso con un código de acceso. Se indica con el método
`protectWithAccessCode`:

```php
$magiclink = MagicLink::create(new DownloadFileAction('private_document.pdf'));

$magiclink->protectWithAccessCode('secret');

$urlToSend = $magiclink->url;
```

El código de acceso es tan fuerte como el valor que elijas: `'secret'` y `'1234'` son ejemplos, no
recomendaciones. Usa un código largo y aleatorio (con la misma entropía que usarías para una
contraseña), sobre todo para cualquier cosa protegida con `LoginAction`.

### Límite de intentos del código de acceso

Por defecto, los intentos fallidos de código de acceso de un magic link están limitados: tras
`max_attempts` fallos seguidos, el enlace se bloquea durante `decay_seconds` y los siguientes
intentos reciben una respuesta `429` con la cabecera `Retry-After`. El límite va por magic link, no
por IP, así que no se puede evitar cambiando de IP. Una cookie de código de acceso válida nunca se
limita ni se cuenta, así que un enlace bloqueado nunca bloquea al visitante que ya se verificó.

```php
// config/magiclink.php
'access_code' => [
    'max_attempts' => env('MAGICLINK_ACCESS_CODE_MAX_ATTEMPTS', 5),
    'decay_seconds' => env('MAGICLINK_ACCESS_CODE_DECAY_SECONDS', 300),
],
```

Pon `max_attempts` a `0` o `'none'` para desactivar el límite.

Este límite usa el almacén de caché por defecto de tu aplicación. Si ese almacén no se comparte entre
todos los procesos que sirven tu app (por ejemplo, el driver `array`, que es local a cada proceso),
el límite no será efectivo entre procesos.

### Vista personalizada para el código de acceso

Puedes personalizar la vista del formulario del código de acceso en el fichero de configuración
`magiclink.php`:

```php
'access_code' => [
    'view' => 'magiclink::access-code', // Cámbiala por tu vista
],
```

Esta es la [vista por defecto](https://github.com/cesargb/laravel-magiclink/blob/master/resources/views/ask-for-access-code-form.blade.php).

## Tiempo de vida

Por defecto, un enlace está disponible durante 72 horas desde su creación. Puedes cambiar su tiempo
de vida, en minutos, con el argumento `$lifetime` del método `create`. Pasa `null` para que el enlace
no caduque nunca.

```php
$lifetime = 60; // 60 minutos

$magiclink = MagicLink::create(new ResponseAction(), $lifetime);

$urlToSend = $magiclink->url;
```

El argumento `$numMaxVisits` indica cuántas veces se puede visitar el enlace. Por defecto es `null`,
es decir, sin límite de visitas.

```php
$lifetime = null; // no caduca nunca
$numMaxVisits = 1; // solo se puede visitar una vez

$magiclink = MagicLink::create(new ResponseAction(), $lifetime, $numMaxVisits);

$urlToSend = $magiclink->url;
```

## Eventos

MagicLink lanza cuatro eventos.

### MagicLinkWasCreated

Evento `MagicLink\Events\MagicLinkWasCreated`, se lanza cuando se crea un magic link.

### MagicLinkWasVisited

Evento `MagicLink\Events\MagicLinkWasVisited`, se lanza cuando se visita un magic link.

### MagicLinkWasDeleted

Evento `MagicLink\Events\MagicLinkWasDeleted`, se lanza cuando se borra individualmente un magic
link caducado. Para activarlo, habilita el borrado al crear en tu fichero `.env`:

```env
# Borra los magic links caducados al crear otros nuevos
MAGICLINK_DELETE_EXPIRED_WHEN_CREATED=true
```

### MagicLinkAccessCodeFailed

Evento `MagicLink\Events\MagicLinkAccessCodeFailed`, se lanza cuando se envía un código de acceso
incorrecto para un enlace protegido con `protectWithAccessCode`. Úsalo para registrar o alertar de
intentos de adivinar el código.

## Personalización

### Configuración

Para personalizar el paquete, publica el fichero de configuración:

```bash
php artisan vendor:publish --provider="MagicLink\MagicLinkServiceProvider" --tag="config"
```

Y edita `config/magiclink.php`.

Por defecto, al crear un MagicLink **no** se borran de la base de datos los magic links caducados.
Puedes activar esa limpieza poniendo `delete_expired_when_created` a `true`:

```php
// config/magiclink.php
'delete_expired_when_created' => env('MAGICLINK_DELETE_EXPIRED_WHEN_CREATED', false),
```

O en tu fichero `.env`:

```env
MAGICLINK_DELETE_EXPIRED_WHEN_CREATED=true
```

> **Consejo:** actives o no la limpieza al crear, plantéate usar el
> [pruning de modelos](#borrar-magiclinks-caducados) de Laravel para limpiar periódicamente los
> enlaces caducados.

### Controlador personalizado

Si pones `disable_default_route` a `true`, eres responsable de registrar tu propia ruta, protegida
con `MagicLink\Middlewares\MagiclinkMiddleware`. Ese middleware ya valida el token (caducidad, límite
de visitas, código de acceso) y guarda el `MagicLink` resuelto en la petición, bajo
`MagiclinkMiddleware::REQUEST_ATTRIBUTE`. Léelo de ahí en lugar de buscar tú el token otra vez; si
no, te saltarías esas comprobaciones.

```php
use Illuminate\Http\Request;
use MagicLink\MagicLink;
use MagicLink\Middlewares\MagiclinkMiddleware;

Route::get('my-magiclink/{token}', function (Request $request) {
    $magicLink = $request->attributes->get(MagiclinkMiddleware::REQUEST_ATTRIBUTE);

    abort_unless($magicLink instanceof MagicLink, 403);

    return $magicLink->run();
})->middleware(MagiclinkMiddleware::class);
```

> **Atención:** si tu ruta (o un `MagiclinkMiddleware` propio o extendido) no guarda ese atributo en
> la petición, `MagicLinkController::access()` busca el token por su cuenta. Ese comportamiento está
> obsoleto y se eliminará en la 3.0. Consulta el
> [CHANGELOG](https://github.com/cesargb/laravel-magiclink/blob/master/CHANGELOG.md) para más
> detalles.

### Migraciones

Para personalizar las migraciones del paquete, publícalas:

```bash
php artisan vendor:publish --provider="MagicLink\MagicLinkServiceProvider" --tag="migrations"
```

Encontrarás los ficheros publicados en `database/migrations/*`.

### Respuesta personalizada cuando el enlace no es válido

Por defecto, un magic link no válido devuelve un estado `403`. Puedes personalizar esta respuesta con
la configuración `magiclink.invalid_response`.

#### Response

Para devolver una respuesta, usa la clase `MagicLink\Responses\Response::class`. Igual que
`response()`, recibe sus argumentos en `options`:

```php
'invalid_response' => [
    'class' => MagicLink\Responses\Response::class,
    'options' => [
        'content' => 'forbidden',
        'status' => 403,
    ],
],
```

#### Abort

Para lanzar una excepción y dejar que el framework gestione la respuesta, usa la clase
`MagicLink\Responses\AbortResponse::class`. Igual que `abort()`, recibe sus argumentos en `options`:

```php
'invalid_response' => [
    'class' => MagicLink\Responses\AbortResponse::class,
    'options' => [
        'message' => 'You Shall Not Pass!',
        'status' => 403,
    ],
],
```

#### Redirect

Usa la clase `MagicLink\Responses\RedirectResponse::class` para devolver un `redirect()`:

```php
'invalid_response' => [
    'class' => MagicLink\Responses\RedirectResponse::class,
    'options' => [
        'to' => '/not_valid_path',
        'status' => 301,
    ],
],
```

#### View

Usa la clase `MagicLink\Responses\ViewResponse::class` para devolver una `view()`:

```php
'invalid_response' => [
    'class' => MagicLink\Responses\ViewResponse::class,
    'options' => [
        'view' => 'invalid',
        'data' => [],
    ],
],
```

## Límite de peticiones

Puedes limitar el número de peticiones por minuto a la ruta de los magic links con la variable de
entorno `MAGICLINK_RATE_LIMIT`.

Por defecto el límite está desactivado (valor `none`). Por ejemplo, para permitir 100 peticiones por
minuto:

```env
MAGICLINK_RATE_LIMIT=100
```

Este límite va por dirección IP y dominio (la firma de throttle por defecto de Laravel), no por magic
link: protege la ruta en conjunto, no un enlace concreto frente a un atacante que pueda cambiar de
IP. Para limitar los intentos de código de acceso de un enlace, consulta el
[límite de intentos del código de acceso](#límite-de-intentos-del-código-de-acceso).

## Borrar MagicLinks caducados

MagicLink usa el [pruning de modelos](https://laravel.com/docs/eloquent#pruning-models) de Laravel,
con el trait `MassPrunable`, para limpiar los enlaces caducados.

Borra los MagicLinks caducados con este comando Artisan:

```bash
php artisan model:prune --model="MagicLink\MagicLink"
```

También puedes borrar los MagicLinks caducados cada vez que se crea uno nuevo poniendo
`MAGICLINK_DELETE_EXPIRED_WHEN_CREATED` a `true` en tu fichero `.env`:

```env
MAGICLINK_DELETE_EXPIRED_WHEN_CREATED=true
```

## Migrar acciones

Los magic links creados con una versión anterior a la 2.24.2 (consulta el
[CHANGELOG](https://github.com/cesargb/laravel-magiclink/blob/master/CHANGELOG.md#2242---2026-01-28))
guardan sus acciones en un formato serializado antiguo y hay que migrarlos.

Para comprobar antes la migración, ejecuta:

```bash
php artisan magiclink:migrate --dry-run
```

Simula la migración y muestra cuántos MagicLinks antiguos ha encontrado. Si todo está bien, ejecuta
la migración real:

```bash
php artisan magiclink:migrate
```

## Contribuir y licencia

Consulta [CONTRIBUTING](https://github.com/cesargb/laravel-magiclink/blob/master/CONTRIBUTING.md) para
más detalles. El paquete se publica bajo licencia MIT, consulta el
[fichero de licencia](https://github.com/cesargb/laravel-magiclink/blob/master/LICENSE.md).
