<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Domain\ValueObjects;

use AqHub\Shared\Domain\ValueObjects\IntIdentifier;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class IntIdentifierTest extends TestCase
{
    #[Test]
    public function should_create_an_identifier(): void
    {
        $result = IntIdentifier::create(1);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(IntIdentifier::class, $result->getData());
        $this->assertSame(1, $result->getData()->getValue());
    }

    #[Test]
    public function should_fail_when_value_is_zero(): void
    {
        $result = IntIdentifier::create(0);

        $this->assertTrue($result->isError());
        $this->assertSame('An identifier must be greater than zero.', $result->getMessage());
    }

    #[Test]
    public function should_fail_when_value_is_negative(): void
    {
        $result = IntIdentifier::create(-5);

        $this->assertTrue($result->isError());
        $this->assertSame('An identifier must be greater than zero.', $result->getMessage());
    }
}
