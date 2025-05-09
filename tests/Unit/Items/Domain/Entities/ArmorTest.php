<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\Entities;

use AqWiki\Items\Domain\ValueObjects\{ItemTags, ItemInfo};
use AqWiki\Items\Domain\Entities\Armor;
use AqWiki\Shared\Domain\Enums\TagType;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class ArmorTest extends TestCase
{
    #[Test]
    public function should_create_armor_instance_and_stores_it_data()
    {
        $guid = 'afdl';
        $name = 'ArchFiend DoomLord';
        $description = "Not even the dark magic of the Shadowscythe is enough to satisfy this soul's lust for power.";
        $tags = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create($name, $description, $tags)->unwrap();

        $armor = Armor::create(
            $guid,
            $itemInfo,
        )->unwrap();

        $this->assertInstanceOf(Armor::class, $armor);
        $this->assertSame($guid, $armor->getGuid());
        $this->assertSame($name, $armor->getName());
        $this->assertSame($description, $armor->getDescription());
        $this->assertSame($tags, $armor->getTags());
    }

    #[Test]
    public function should_fail_because_armor_guid_is_empty()
    {
        $guid = '';
        $name = 'Necrotic Sword of Doom';
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $tags = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create($name, $description, $tags)->unwrap();

        $result = Armor::create(
            $guid,
            $itemInfo,
        );

        $this->assertNotInstanceOf(Armor::class, $result->getData());
        $this->assertNull($result->getData());
        $this->assertSame($result->getMessage(), 'The GUID of an armor cant be empty.');
    }
}
