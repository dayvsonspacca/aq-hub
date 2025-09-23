<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqWiki\Items\Domain\ValueObjects\{ItemTags, ItemInfo, Name};
use AqWiki\Shared\Domain\Enums\TagType;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class ItemInfoTest extends TestCase
{
    #[Test]
    public function should_create_item_info_instance_and_stores_it_data()
    {
        $name = 'Necrotic Sword of Doom';
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $tags = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), $description, $tags)->unwrap();

        $this->assertInstanceOf(ItemInfo::class, $itemInfo);
        $this->assertSame($name, $itemInfo->getName());
        $this->assertSame($description, $itemInfo->getDescription());
        $this->assertSame($tags, $itemInfo->getTags());
    }

    #[Test]
    public function should_fail_because_item_info_description_is_empty()
    {
        $name = 'Necrotic Sword of Doom';
        $description = '';
        $tags = new ItemTags([TagType::AdventureCoins]);

        $result = ItemInfo::create(Name::create($name)->unwrap(), $description, $tags);

        $this->assertNotInstanceOf(ItemInfo::class, $result->getData());
        $this->assertNull($result->getData());
        $this->assertTrue($result->isError());
        $this->assertSame($result->getMessage(), 'The description of an item cant be empty.');
    }
}
