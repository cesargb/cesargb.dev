<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\MetaRobotsMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class MetaRobotsMiddlewareTest extends TestCase
{
    public function test_sets_the_default_robots_value_in_context(): void
    {
        $response = (new MetaRobotsMiddleware)->handle(
            Request::create('/'),
            fn () => new Response('ok'),
        );

        $this->assertSame('ok', $response->getContent());
        $this->assertSame('noindex, nofollow', Context::getHidden('meta.robots'));
    }

    public function test_accepts_a_custom_robots_value(): void
    {
        (new MetaRobotsMiddleware)->handle(
            Request::create('/'),
            fn () => new Response('ok'),
            'index,follow',
        );

        $this->assertSame('index,follow', Context::getHidden('meta.robots'));
    }
}
