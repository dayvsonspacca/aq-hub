<?php

declare(strict_types=1);

namespace AqHub\Tests\Integration\Items\Infrastructure\Http\Forms\Fields;

use AqHub\Items\Domain\ValueObjects\Name;
use AqHub\Items\Infrastructure\Http\Forms\Fields\NameField;
use AqHub\Tests\Unit\TestRequests;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;

final class NameFieldTest extends TestRequests
{
    #[Test]
    public function should_create_name_correct()
    {
        $request = $this->createRequest(['name' => 'awe']);
        $name = NameField::fromRequest($request);

        $this->assertInstanceOf(Name::class, $name);
        $this->assertSame('awe', $name->value);
    }

    #[Test]
    public function should_fail_when_name_present_in_query_but_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The name of an item cant be empty.');

        $request = $this->createRequest(['name' => '   ']);
        $name = NameField::fromRequest($request);
    }
}
