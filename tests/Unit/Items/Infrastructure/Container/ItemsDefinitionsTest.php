<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Container;

use AqHub\Items\Infrastructure\Container\ItemsDefinitions;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\TestCase;

final class ItemsDefinitionsTest extends TestCase
{
    #[Test]
    public function should_return_array()
    {
        $this->assertIsArray(ItemsDefinitions::dependencies());
    }
}
