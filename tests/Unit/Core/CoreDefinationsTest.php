<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core;

use AqHub\Core\ContainerFactory;
use AqHub\Core\CoreDefinations;
use AqHub\Core\Env;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\TestCase;
use DI\Container;

final class CoreDefinationsTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = ContainerFactory::make(CoreDefinations::dependencies());
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