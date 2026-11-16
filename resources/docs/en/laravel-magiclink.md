# Laravel MagicLink

Laravel MagicLink generates secure, temporary links that run an action when they are opened:
log a user in, show a private file, render a view or call your own code.

## Installation

```bash
composer require cesargb/laravel-magiclink
```

Publish and run the migration that creates the links table:

```bash
php artisan vendor:publish --provider="MagicLink\MagicLinkServiceProvider" --tag=migrations
php artisan migrate
```

## Quick start

Create a link that logs a user in without a password:

```php
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;

$action = new LoginAction(User::first());

$url = MagicLink::create($action)->url;
```

Send `$url` to the user (by email, for example). When they open it, they are signed in.

## More information

The full reference lives in the [GitHub repository](https://github.com/cesargb/laravel-magiclink).
