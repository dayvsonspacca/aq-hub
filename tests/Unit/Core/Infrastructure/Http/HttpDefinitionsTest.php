<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core\Infrastructure\Http;

use AqHub\Core\ContainerFactory;
use AqHub\Core\Infrastructure\Http\{HttpDefinitions, HttpHandler};
use AqHub\Tests\TestCase;
use DI\Container;
use PHPUnit\Framework\Attributes\Test;

final class HttpDefinitionsTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = ContainerFactory::make([HttpDefinitions::dependencies()]);
    }

    #[Test]
    public function should_return_dependencies()
    {
        $dependencies = HttpDefinitions::dependencies();

        $this->assertCount(1, $dependencies);
    }

    #[Test]
    public function should_have_http_definitions()
    {
        $this->assertTrue($this->container->has(HttpHandler::class));
    }
}
