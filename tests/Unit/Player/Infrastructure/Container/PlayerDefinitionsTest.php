<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Player\Infrastructure\Container;

use AqHub\Player\Infrastructure\Container\PlayerDefinitions;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class PlayerDefinitionsTest extends TestCase
{
    #[Test]
    public function should_return_array()
    {
        $this->assertIsArray(PlayerDefinitions::dependencies());
    }
}
