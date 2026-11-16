# Laravel MagicLink

The `MagicLink` class creates secure links that perform an action when they are visited. Use them to
serve private content or even to log users in to your application.

> **Upgrading?** See the [CHANGELOG](https://github.com/cesargb/laravel-magiclink/blob/master/CHANGELOG.md)
> for what changed and any required migration steps.

## Installation

Install the package via Composer:

```bash
composer require cesargb/laravel-magiclink
```

### Preparing the database

Publish the migration that creates the `magic_links` table:

```bash
php artisan vendor:publish --provider="MagicLink\MagicLinkServiceProvider" --tag="migrations"
```

Then run the migrations:

```bash
php artisan migrate
```

## Use case

This example creates a link that logs the given user in to your application:

```php
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;

$urlToAutoLogin = MagicLink::create(new LoginAction($user))->url;
```

## Create a MagicLink

The `MagicLink::create` method takes the action to perform and returns a `MagicLink` instance. Its
`url` property is the link you send to your visitor.

## Actions

Each MagicLink is associated with an action, which is performed once the link is visited.

- [Login Action](#login-action)
- [Download file Action](#download-file-action)
- [Inline file Action](#inline-file-action)
- [View Action](#view-action)
- [Http Response Action](#http-response-action)
- [Controller Action](#controller-action)
- [Custom Action](#custom-action)
- [Custom Base URL](#custom-base-url)

### Login Action

The `LoginAction` logs a user in to the application through the generated link.

Its constructor takes the user to log in. Optionally, set the
[HTTP response](https://laravel.com/docs/responses) with the `response` method, or use another guard
with the `guard` method.

```php
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;

// Example 1: log in and redirect to the dashboard
$action = new LoginAction(User::first());
$action->response(redirect('/dashboard'));

$urlToDashboard = MagicLink::create($action)->url;

// Example 2: log in and show the password reset form, using the web guard
$action = new LoginAction(User::first());
$action->response(view('password.reset', ['email' => 'user@example.tld']));

$urlShowView = MagicLink::create($action)->url;

// Example 3: log in with another guard and use the default redirect
$action = new LoginAction(User::first());
$action->guard('customguard');

$urlShowView = MagicLink::create($action)->url;

// Example 4: log in and remember the user
$action = new LoginAction(User::first());
$action->remember();

$urlShowView = MagicLink::create($action)->url;
```

### Download file Action

The `DownloadFileAction` creates a link to download a private file. Its constructor takes the file
path.

```php
use MagicLink\Actions\DownloadFileAction;
use MagicLink\MagicLink;

// URL to download the file storage_app('private_document.pdf')
$url = MagicLink::create(new DownloadFileAction('private_document.pdf'))->url;

// Download the file with another file name
$action = new DownloadFileAction('private_document.pdf', 'your_document.pdf');
$url = MagicLink::create($action)->url;

// Download the file from another disk
$action = (new DownloadFileAction('private_document.pdf'))->disk('ftp');
$url = MagicLink::create($action)->url;
```

### Inline file Action

The `InlineFileAction` creates a link that shows a private file inline in the browser (a PDF or an
image, for example) instead of forcing its download.

It takes the same arguments and fluent methods as `DownloadFileAction`: file path, file name,
`disk()` and `headers()`.

```php
use MagicLink\Actions\InlineFileAction;
use MagicLink\MagicLink;

// URL to show the file storage_app('private_document.pdf') in the browser
$url = MagicLink::create(new InlineFileAction('private_document.pdf'))->url;

// Show the file with another file name
$action = new InlineFileAction('private_document.pdf', 'your_document.pdf');
$url = MagicLink::create($action)->url;

// Show the file from another disk
$action = (new InlineFileAction('private_document.pdf'))->disk('ftp');
$url = MagicLink::create($action)->url;
```

### View Action

The `ViewAction` gives access to a view. Its constructor takes the same arguments as Laravel's `view`
helper.

```php
use MagicLink\Actions\ViewAction;
use MagicLink\MagicLink;

// URL to the internal.blade.php view
$url = MagicLink::create(new ViewAction('internal', [
    'data' => 'Your private custom content',
]))->url;
```

### Http Response Action

The `ResponseAction` gives access to private content without logging in. Its constructor takes the
[HTTP response](https://laravel.com/docs/responses) returned by the request.

```php
use MagicLink\Actions\ResponseAction;
use MagicLink\MagicLink;

$action = new ResponseAction(function () {
    Auth::login(User::first());

    return redirect('/change_password');
});

$urlToCustomFunction = MagicLink::create($action)->url;
```

### Controller Action

The `ControllerAction` calls a controller directly.

Its constructor takes the controller class name. The optional second argument is the method to call;
by default it uses `__invoke`.

```php
use MagicLink\Actions\ControllerAction;
use MagicLink\MagicLink;

// Call the __invoke method of the controller
$url = MagicLink::create(new ControllerAction(MyController::class))->url;

// Call the show method of the controller
$url = MagicLink::create(new ControllerAction(MyController::class, 'show'))->url;
```

### Custom Action

You can create your own action class by extending `MagicLink\Actions\ActionAbstract`:

```php
use MagicLink\Actions\ActionAbstract;

class MyCustomAction extends ActionAbstract
{
    public function __construct(public string $variable)
    {
    }

    public function run()
    {
        // Do something

        return response()->json([
            'success' => true,
            'data' => $this->variable,
        ]);
    }
}
```

Now you can create a MagicLink with your custom action:

```php
use MagicLink\MagicLink;

$action = new MyCustomAction('Hello world');

$urlToCustomAction = MagicLink::create($action)->url;
```

#### Allowed classes for custom actions

> **Best practice:** use primitive types (`int`, `string`, `array`…) as properties instead of
> objects. Extract the data you need from objects in the constructor and store only the primitive
> values.
>
> For example, `LoginAction` receives a `User` object but stores only its `authIdentifier`
> (int/string) instead of the whole object. This is more secure and doesn't require configuring
> allowed classes.

When your custom action **must** hold object properties (like Eloquent models), you have to
explicitly allow those classes to be deserialized. This prevents security vulnerabilities from
arbitrary object instantiation.

**Recommended approach (primitive types):**

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
        // Send email to $user

        return response('Email sent!');
    }
}

// Usage
$action = new SendWelcomeEmailAction($user->id, $user->email);
MagicLink::create($action);
```

**Alternative approach (objects, requires configuration):**

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
        // Send email to $this->user

        return response('Email sent!');
    }
}
```

To allow the `User` class to be deserialized, add it in `config/magiclink.php`:

```php
'allowed_classes' => [
    \App\Models\User::class,
    \App\Models\Post::class,
    // Add any other classes your actions might use
],
```

Or use the `MAGICLINK_ALLOWED_CLASSES` environment variable with comma-separated class names:

```env
MAGICLINK_ALLOWED_CLASSES="App\Models\User,App\Models\Post"
```

> **Warning:** if you use object properties in your custom actions without configuring the allowed
> classes, you'll get a `TypeError` when the MagicLink is visited. Always add your classes to the
> allowed list.

### Custom Base URL

Use the `baseUrl` method to set the base URL of a magic link. It makes sure the given base URL ends
with a trailing slash, ready for URL generation.

```php
$magiclink = MagicLink::create($action);

$magiclink->baseUrl('http://example.com');

$urlShowView = $magiclink->url; // http://example.com/magiclink/...
```

## Protect with an access code

Optionally, you can protect the resource with an access code. Set it with the
`protectWithAccessCode` method:

```php
$magiclink = MagicLink::create(new DownloadFileAction('private_document.pdf'));

$magiclink->protectWithAccessCode('secret');

$urlToSend = $magiclink->url;
```

The access code is only as strong as the value you choose: `'secret'` and `'1234'` are examples, not
recommendations. Prefer a long, random code (the same kind of entropy you'd use for a password),
especially for anything protected with `LoginAction`.

### Access code attempt limiting

Wrong access code guesses for a magic link are throttled by default: after `max_attempts`
consecutive wrong guesses, the link is locked for `decay_seconds`, and further attempts get a `429`
response with a `Retry-After` header. The limit is keyed per magic link, not per IP, so it can't be
evaded by rotating IPs. A valid access code cookie is never throttled or counted, so a locked link
never blocks its own already verified visitor.

```php
// config/magiclink.php
'access_code' => [
    'max_attempts' => env('MAGICLINK_ACCESS_CODE_MAX_ATTEMPTS', 5),
    'decay_seconds' => env('MAGICLINK_ACCESS_CODE_DECAY_SECONDS', 300),
],
```

Set `max_attempts` to `0` or `'none'` to disable the limiter.

This limiter uses your application's default cache store. If that store isn't shared across all the
processes serving your app (for example, the process-local `array` driver), the limit won't be
effective across processes.

### Custom view for the access code

You can customize the access code form view in the `magiclink.php` config file:

```php
'access_code' => [
    'view' => 'magiclink::access-code', // Change it to your view
],
```

This is the [default view](https://github.com/cesargb/laravel-magiclink/blob/master/resources/views/ask-for-access-code-form.blade.php).

## Lifetime

By default, a link is available for 72 hours after it's created. You can change the lifetime in
minutes with the `$lifetime` argument of the `create` method. Pass `null` so the link never expires.

```php
$lifetime = 60; // 60 minutes

$magiclink = MagicLink::create(new ResponseAction(), $lifetime);

$urlToSend = $magiclink->url;
```

The `$numMaxVisits` argument sets how many times the link can be visited. By default it's `null`,
which means there's no visit limit.

```php
$lifetime = null; // never expires
$numMaxVisits = 1; // can be visited only once

$magiclink = MagicLink::create(new ResponseAction(), $lifetime, $numMaxVisits);

$urlToSend = $magiclink->url;
```

## Events

MagicLink fires four events.

### MagicLinkWasCreated

Event `MagicLink\Events\MagicLinkWasCreated`, fired when a magic link is created.

### MagicLinkWasVisited

Event `MagicLink\Events\MagicLinkWasVisited`, fired when a magic link is visited.

### MagicLinkWasDeleted

Event `MagicLink\Events\MagicLinkWasDeleted`, fired when an expired magic link is deleted
individually. To enable it, turn on deletion on creation in your `.env` file:

```env
# Delete expired magic links when creating new ones
MAGICLINK_DELETE_EXPIRED_WHEN_CREATED=true
```

### MagicLinkAccessCodeFailed

Event `MagicLink\Events\MagicLinkAccessCodeFailed`, fired when a wrong access code is submitted for a
link protected with `protectWithAccessCode`. Use it to log or alert on access code guessing attempts.

## Customization

### Config

To customize the package, publish the config file:

```bash
php artisan vendor:publish --provider="MagicLink\MagicLinkServiceProvider" --tag="config"
```

And edit `config/magiclink.php`.

By default, creating a new MagicLink does **not** delete expired magic links from the database. You
can enable that cleanup by setting `delete_expired_when_created` to `true`:

```php
// config/magiclink.php
'delete_expired_when_created' => env('MAGICLINK_DELETE_EXPIRED_WHEN_CREATED', false),
```

Or in your `.env` file:

```env
MAGICLINK_DELETE_EXPIRED_WHEN_CREATED=true
```

> **Tip:** whether or not you enable cleanup on creation, consider using Laravel's
> [model pruning](#delete-expired-magiclinks) to periodically clean up expired links.

### Custom controller

If you set `disable_default_route` to `true`, you're responsible for registering your own route,
guarded by `MagicLink\Middlewares\MagiclinkMiddleware`. That middleware already validates the token
(expiration, visit limits, access code) and stores the resolved `MagicLink` on the request under
`MagiclinkMiddleware::REQUEST_ATTRIBUTE`. Read it from there instead of looking the token up again
yourself, otherwise you'd skip those checks.

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

> **Warning:** if your route (or a custom or extended `MagiclinkMiddleware`) doesn't set that request
> attribute, `MagicLinkController::access()` currently falls back to looking the token up itself.
> That fallback is deprecated and will be removed in 3.0. See the
> [CHANGELOG](https://github.com/cesargb/laravel-magiclink/blob/master/CHANGELOG.md) for details.

### Migrations

To customize the package's migration files, publish them:

```bash
php artisan vendor:publish --provider="MagicLink\MagicLinkServiceProvider" --tag="migrations"
```

You'll find the published files in `database/migrations/*`.

### Custom response when the magic link is invalid

By default, an invalid magic link returns a `403` status. You can customize this response with the
`magiclink.invalid_response` config.

#### Response

To return a response, use the `MagicLink\Responses\Response::class` class. Like `response()`, it
takes its arguments through `options`:

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

To throw an exception and let the framework handle the response, use the
`MagicLink\Responses\AbortResponse::class` class. Like `abort()`, it takes its arguments through
`options`:

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

Use the `MagicLink\Responses\RedirectResponse::class` class to return a `redirect()`:

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

Use the `MagicLink\Responses\ViewResponse::class` class to return a `view()`:

```php
'invalid_response' => [
    'class' => MagicLink\Responses\ViewResponse::class,
    'options' => [
        'view' => 'invalid',
        'data' => [],
    ],
],
```

## Rate limiting

You can limit the number of requests per minute to the magic link route by setting the
`MAGICLINK_RATE_LIMIT` environment variable.

The rate limit is disabled by default (value `none`). For example, to allow 100 requests per minute:

```env
MAGICLINK_RATE_LIMIT=100
```

This limit is keyed by IP address and domain (Laravel's default throttle signature), not by magic
link: it protects the route as a whole, not an individual link from a determined attacker who can
rotate IPs. To limit access code guesses on a single link, see
[Access code attempt limiting](#access-code-attempt-limiting).

## Delete expired MagicLinks

MagicLink uses Laravel's [model pruning](https://laravel.com/docs/eloquent#pruning-models) through
the `MassPrunable` trait to clean up expired links.

Prune expired MagicLinks with the following Artisan command:

```bash
php artisan model:prune --model="MagicLink\MagicLink"
```

You can also delete expired MagicLinks every time a new one is created by setting
`MAGICLINK_DELETE_EXPIRED_WHEN_CREATED` to `true` in your `.env` file:

```env
MAGICLINK_DELETE_EXPIRED_WHEN_CREATED=true
```

## Migrate actions

Magic links created with a version prior to 2.24.2 (see the
[CHANGELOG](https://github.com/cesargb/laravel-magiclink/blob/master/CHANGELOG.md#2242---2026-01-28))
store their actions in a legacy serialized format and need to be migrated.

To check the migration first, run:

```bash
php artisan magiclink:migrate --dry-run
```

This simulates the migration and shows how many legacy MagicLinks were found. If everything looks
good, run the migration for real:

```bash
php artisan magiclink:migrate
```

## Contributing and license

Please see [CONTRIBUTING](https://github.com/cesargb/laravel-magiclink/blob/master/CONTRIBUTING.md) for
details. The package is released under the MIT License, see the
[license file](https://github.com/cesargb/laravel-magiclink/blob/master/LICENSE.md).
