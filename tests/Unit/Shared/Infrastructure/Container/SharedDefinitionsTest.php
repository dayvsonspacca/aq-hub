<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core\Infrastructure\Database;

use AqHub\Shared\Infrastructure\Container\SharedDefinitions;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class SharedDefinitionsTest extends TestCase
{
    #[Test]
    public function should_return_array()
    {
        $this->assertIsArray(SharedDefinitions::dependencies());
    }
}
