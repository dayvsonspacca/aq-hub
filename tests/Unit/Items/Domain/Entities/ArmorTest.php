<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Entities;

use AqHub\Items\Domain\Entities\Armor;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ArmorTest extends TestCase
{
    #[Test]
    public function should_create_armor_instance_and_stores_it_data()
    {
        $name        = 'ArchFiend DoomLord';
        $description = "Not even the dark magic of the Shadowscythe is enough to satisfy this soul's lust for power.";
        $tags        = new ItemTags([ItemTag::AdventureCoins]);
        $itemInfo    = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags, ItemRarity::Epic)->unwrap();
        $id          = ItemIdentifierGenerator::generate($itemInfo, Armor::class)->unwrap();

        $armor = Armor::create(
            $id,
            $itemInfo,
        )->unwrap();

        $this->assertInstanceOf(Armor::class, $armor);
        $this->assertSame($id->getValue(), $armor->getId());
        $this->assertSame($name, $armor->getName());
        $this->assertSame($description, $armor->getDescription());
        $this->assertSame($tags, $armor->getTags());
    }
}
