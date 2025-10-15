<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Repositories\Filters;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\Filters\CanFilterRarities;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class CanFilterRaritiesTest extends TestCase
{
    #[Test]
    public function should_create_can_filter_rarities_with_default_values()
    {
        $filter = new class () {
            use CanFilterRarities;
        };

        $this->assertEquals([], $filter->rarities);
    }

    #[Test]
    public function should_can_change_rarities()
    {
        $filter = new class () {
            use CanFilterRarities;
        };

        $this->assertEquals([], $filter->rarities);

        $rarities = [ItemRarity::Legendary, ItemRarity::Epic];
        $filter->setRarities($rarities);

        $this->assertSame($filter->rarities, $rarities);
    }
}
