<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core\Infrastructure\Http;

use AqHub\Core\Infrastructure\Http\JwtAuthMiddleware;
use AqHub\Core\Infrastructure\Http\Route;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    #[Test]
    public function should_be_created_with_correct_path_and_methods()
    {
        $path    = '/armors/list';
        $methods = ['GET'];
        $middlewares = [JwtAuthMiddleware::class];

        $route = new Route($path, $methods, $middlewares);

        $this->assertSame($path, $route->path);
        $this->assertSame($methods, $route->methods);
    }
}
