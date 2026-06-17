<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\AppMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AppMiddlewareTest extends TestCase
{
    public function test_forces_noindex_outside_production(): void
    {
        (new AppMiddleware)->handle(
            Request::create('/'),
            fn () => new Response('ok'),
        );

        $this->assertSame('noindex,nofollow', Context::getHidden('meta.robots'));
    }

    public function test_does_not_touch_robots_in_production(): void
    {
        // PHPUnit runs in console, so Cesargb::isProduction() returns true
        // as soon as the environment is production (see Cesargb::isProduction()).
        $this->app->detectEnvironment(fn () => 'production');

        (new AppMiddleware)->handle(
            Request::create('/'),
            fn () => new Response('ok'),
        );

        $this->assertNull(Context::getHidden('meta.robots'));
    }
}
