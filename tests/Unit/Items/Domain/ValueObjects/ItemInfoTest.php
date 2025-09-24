<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqHub\Items\Domain\ValueObjects\{Description, ItemTags, ItemInfo, Name};
use AqHub\Shared\Domain\Enums\TagType;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\Unit\TestCase;

final class ItemInfoTest extends TestCase
{
    #[Test]
    public function should_create_item_info_instance_and_stores_it_data()
    {
        $name        = 'Necrotic Sword of Doom';
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $tags        = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags)->unwrap();

        $this->assertInstanceOf(ItemInfo::class, $itemInfo);
        $this->assertSame($name, $itemInfo->getName());
        $this->assertSame($description, $itemInfo->getDescription());
        $this->assertSame($tags, $itemInfo->getTags());
    }
}
