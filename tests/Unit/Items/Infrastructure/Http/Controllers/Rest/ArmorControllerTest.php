<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Controllers\Rest;

use AqHub\Core\ContainerFactory;
use AqHub\Core\CoreDefinitions;
use AqHub\Core\Infrastructure\Database\DatabaseDefinitions;
use AqHub\Core\Infrastructure\Http\HttpDefinitions;
use AqHub\Items\Infrastructure\Http\Controllers\Rest\ArmorController;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\DoRequests;
use DI\Container;
use PHPUnit\Framework\Attributes\Test;

class ArmorControllerTest extends TestCase
{
    use DoRequests;

    private Container $container;
    private ArmorController $controller;

    protected function setUp(): void
    {
        $this->container = ContainerFactory::make(array_merge(
            CoreDefinitions::dependencies(),
            HttpDefinitions::dependencies(),
            DatabaseDefinitions::dependencies()
        ));

        $this->controller = $this->container->get(ArmorController::class);
    }

    #[Test]
    public function should_create_armor_controller()
    {
        $this->assertInstanceOf(ArmorController::class, $this->controller);
    }

    #[Test]
    public function should_list_armors()
    {
        $this->assertNull($this->controller->list($this->makeRequest()));
    }
}
