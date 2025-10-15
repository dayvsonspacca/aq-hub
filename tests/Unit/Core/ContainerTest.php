<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core;

use AqHub\Core\Container;
use AqHub\Tests\Unit\TestCase;
use DI\Container as DIContainer;
use PHPUnit\Framework\Attributes\Test;

final class ContainerTest extends TestCase
{
    #[Test]
    public function should_create_container()
    {
        $container = Container::factory([]);

        $this->assertInstanceOf(DIContainer::class, $container);
    }
}