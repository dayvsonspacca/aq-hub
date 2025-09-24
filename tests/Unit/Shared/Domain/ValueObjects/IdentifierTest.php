<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use AqHub\Shared\Domain\ValueObjects\Identifier;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\Unit\TestCase;

final class IdentifierTest extends TestCase
{
    #[Test]
    public function should_create_an_identifier(): void
    {
        $result = Identifier::create(1);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(Identifier::class, $result->getData());
        $this->assertSame(1, $result->getData()->getValue());
    }

    #[Test]
    public function should_fail_when_value_is_zero(): void
    {
        $result = Identifier::create(0);

        $this->assertTrue($result->isError());
        $this->assertSame('An identifier must be greater than zero.', $result->getMessage());
    }

    #[Test]
    public function should_fail_when_value_is_negative(): void
    {
        $result = Identifier::create(-5);

        $this->assertTrue($result->isError());
        $this->assertSame('An identifier must be greater than zero.', $result->getMessage());
    }
}
