<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Controllers;

use AqHub\Items\Infrastructure\Http\Controllers\ArmorController;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\DoRequests;
use PHPUnit\Framework\Attributes\Test;

class ArmorControllerTest extends TestCase
{
    use DoRequests;

    #[Test]
    public function should_create_armor_controller()
    {
        $controller = new ArmorController();

        $this->assertInstanceOf(ArmorController::class, $controller);
    }

    #[Test]
    public function should_list_armors()
    {
        $controller = new ArmorController();

        $this->assertNull($controller->list($this->makeRequest()));
    }
}
