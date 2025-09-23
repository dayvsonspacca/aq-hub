<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqWiki\Items\Domain\ValueObjects\Description;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class DescriptionTest extends TestCase
{
    #[Test]
    public function should_create_description_and_store_it_data()
    {
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $result      = Description::create($description);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(Description::class, $result->getData());
        $this->assertSame($description, $result->getData()->value);
    }

    #[Test]
    public function should_fail_when_description_empty()
    {
        $description = '';
        $result      = Description::create($description);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
    }
}
