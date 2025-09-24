<?php

declare(strict_types=1);

namespace Tests\Unit\Player\Domain\ValueObjects;

use AqHub\Player\Domain\ValueObjects\Name;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\Unit\TestCase;

final class NameTest extends TestCase
{
    #[Test]
    public function should_create_name_and_store_it_data()
    {
        $name   = 'Hilise';
        $result = Name::create($name);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(Name::class, $result->getData());
        $this->assertSame($name, $result->getData()->value);
    }

    #[Test]
    public function should_fail_when_name_empty()
    {
        $name   = '';
        $result = Name::create($name);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
        $this->assertSame($result->getMessage(), 'The name of a player cant be empty.');
    }
}
