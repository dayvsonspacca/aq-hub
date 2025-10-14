<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Infrastructure\Http;

use AqHub\Shared\Infrastructure\Http\Route;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    #[Test]
    public function should_be_created_with_correct_path_and_methods()
    {
        $path    = '/armors/list';
        $methods = ['GET'];

        $route = new Route($path, $methods);

        $this->assertSame($path, $route->path);
        $this->assertSame($methods, $route->methods);
    }
}
