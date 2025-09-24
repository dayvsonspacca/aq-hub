<?php

declare(strict_types=1);

namespace Tests\Unit\Player\Domain\ValueObjects;

use AqHub\Player\Domain\ValueObjects\Level;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\Unit\TestCase;

final class NameTest extends TestCase
{
    #[Test]
    public function should_create_level_and_store_it_data()
    {
        $level   = 100;
        $result = Level::create($level);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(Level::class, $result->getData());
        $this->assertSame($level, $result->getData()->value);
    }

    #[Test]
    public function should_fail_when_level_is_greater_than_max()
    {
        $level  = Level::MAX + 1;
        $result = Level::create($level);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
        $this->assertSame($result->getMessage(), 'The level of the player needs to be in the range of 1 - 100 | informed: 101');
    }

    #[Test]
    public function should_fail_when_level_is_less_than_max()
    {
        $level  = Level::MIN - 1;
        $result = Level::create($level);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
        $this->assertSame($result->getMessage(), 'The level of the player needs to be in the range of 1 - 100 | informed: 0');
    }
}
