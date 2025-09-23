<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqWiki\Items\Domain\ValueObjects\Name;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class NameTest extends TestCase
{
    #[Test]
    public function should_create_name_and_store_it_data()
    {
        $name = 'Necrotic Sword of Doom';
        $result = Name::create($name);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(Name::class, $result->getData());
        $this->assertSame($name, $result->getData()->value);
    }

    #[Test]
    public function should_fail_when_name_empty()
    {
        $name = '';
        $result = Name::create($name);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
    }
}
