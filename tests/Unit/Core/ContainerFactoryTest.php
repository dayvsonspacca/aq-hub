<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core;

use AqHub\Core\ContainerFactory;
use AqHub\Tests\TestCase;
use DI\Container as DIContainer;
use PHPUnit\Framework\Attributes\Test;

final class ContainerFactoryTest extends TestCase
{
    #[Test]
    public function should_create_container()
    {
        $container = ContainerFactory::make([]);

        $this->assertInstanceOf(DIContainer::class, $container);
    }
}
