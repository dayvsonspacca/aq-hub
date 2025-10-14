<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Domain\ValueObjects;

use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class StringIdentifierTest extends TestCase
{
    #[Test]
    public function should_create_a_string_identifier(): void
    {
        $result = StringIdentifier::create('abc123');

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(StringIdentifier::class, $result->getData());
        $this->assertSame('abc123', $result->getData()->getValue());
    }

    #[Test]
    public function should_fail_when_string_is_empty(): void
    {
        $result = StringIdentifier::create('');

        $this->assertTrue($result->isError());
        $this->assertSame('A string identifier cannot be empty.', $result->getMessage());
    }

    #[Test]
    public function should_fail_when_string_is_whitespace(): void
    {
        $result = StringIdentifier::create('   ');

        $this->assertTrue($result->isError());
        $this->assertSame('A string identifier cannot be empty.', $result->getMessage());
    }
}
