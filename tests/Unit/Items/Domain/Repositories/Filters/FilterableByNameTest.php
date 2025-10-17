<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Repositories\Filters;

use AqHub\Items\Domain\Repositories\Filters\FilterableByName;
use AqHub\Items\Domain\ValueObjects\Name;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class FilterableByNameTest extends TestCase
{
    #[Test]
    public function should_create_can_filter_name_with_default_values()
    {
        $filter = new class () {
            use FilterableByName;
        };

        $this->assertNull($filter->name);
    }

    #[Test]
    public function should_can_change_name()
    {
        $filter = new class () {
            use FilterableByName;
        };

        $this->assertNull($filter->name);

        $name = Name::create('Archifiend Doomlord')->unwrap();
        $filter->setName($name);

        $this->assertSame($filter->name, $name);
    }
}
