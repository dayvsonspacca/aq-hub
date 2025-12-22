<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core;

use AqHub\Core\{ContainerFactory, CoreDefinitions, Env};
use AqHub\Tests\TestCase;
use DI\Container;
use PHPUnit\Framework\Attributes\Test;

final class CoreDefinitionsTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = ContainerFactory::make([CoreDefinitions::dependencies()]);
    }

    #[Test]
    public function should_return_dependencies()
    {
        $dependencies = CoreDefinitions::dependencies();

        $this->assertCount(4, $dependencies);
    }

    #[Test]
    public function should_have_core_paths()
    {
        $this->assertTrue($this->container->has('Path.Root'));
        $this->assertTrue($this->container->has('Path.Cache'));
        $this->assertTrue($this->container->has('Path.Logs'));
    }

    #[Test]
    public function should_have_env()
    {
        $this->assertTrue($this->container->has(Env::class));
    }
}
